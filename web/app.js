// TODO: Replace this POC with something better, either an App object or something
// else based on existing libraries

function initApp(config) {
    var client = algoliasearch(config.algolia_applicationID, config.algolia_apiKey);
    var index = client.initIndex(config.algolia_indexName);

    var input = document.getElementById('searchInput');
    var addButton = document.getElementById('addButton');
    var resultsEl = document.getElementById('results');

    input.addEventListener('keyup', function (e) {
        var toSearch = this.value;

        if (toSearch === '' && resultsEl.hasChildNodes()) {
            resultsEl.removeChild(resultsEl.firstChild);
        } else {
            // the last optional argument can be used to add search parameters
            index.search(
                toSearch, {
                    hitsPerPage: 5,
                    facets: '*',
                    maxValuesPerFacet: 10
                },
                searchCallback
            );
        }
    });

    // TODO: do something better, this is only to test
    addButton.addEventListener('click', function (e) {
        var json = window.prompt('Paste json object');

        if (json !== '') {
            // TODO: what should happen next?
            addEntity(json);
        }
    })

    function searchCallback(err, content) {
        if (err) {
            console.error(err);
            return;
        } else {
            if (resultsEl.hasChildNodes()) {
                resultsEl.removeChild(resultsEl.firstChild);
            }

            if (content.hits.length > 0) {
                var i;
                var listContainer = document.createElement('ul');

                for (i = 0; i < content.hits.length; i++) {
                    listContainer.appendChild(createResultRow(content.hits[i]));
                }

                resultsEl.appendChild(listContainer);
            }
        }

        function createResultRow(content) {
            // TODO: use data-objectid to store the id
            // TODO: would be better to have a global event listener instead of
            // one per element
            var newEl = document.createElement('li');
            var elContent = document.createTextNode(content.name);
            var deleteButton = document.createElement('button');
            var deleteButtonContent = document.createTextNode('Delete');
            deleteButton.appendChild(deleteButtonContent);
            newEl.appendChild(elContent);
            newEl.appendChild(deleteButton);

            deleteButton.addEventListener('click', function (e) {
                deleteEntity(content.objectID);
            });

            return newEl;
        }

        console.log(content);
    }

    function deleteEntity(id, askToConfirm) {
        if (askToConfirm === undefined) {
            askToConfirm = true;
        }

        // TODO: ask for confirmation

        // TODO: id is already a string, is this needed?
        fetch('/api/1/apps/' + id.toString(), {method: 'DELETE'})
        .then(function (response) {
            console.log(response);
        });
    }

    function addEntity(json) {
        var data = new FormData();
        data.append('data', json);

        fetch('/api/1/apps', {
            method: 'POST',
            body: data,
        }).then(function (response) {
            console.log(response);
        }).catch(function (error) {
            console.error(error);
        });
    }
}