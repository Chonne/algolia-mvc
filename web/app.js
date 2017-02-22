// TODO: Replace this POC with something better, either an App object or something
// else based on existing libraries

function initApp(config) {
    var client = algoliasearch(config.algolia_applicationID, config.algolia_apiKey);
    var index = client.initIndex(config.algolia_indexName);

    var searchInput = document.getElementById('searchInput');
    var addButton = document.getElementById('addButton');
    var resultsEl = document.getElementById('results');
    var resultTemplateContent = document.getElementById('resultTpl').content;

    // Click event on the delete buttons
    resultsEl.addEventListener('click', function (e) {
        if (e.target.type === 'button' && e.target.classList.contains('delete')) {
            var objectID = e.target.parentElement.dataset.id;
            deleteEntity(objectID);

            // TODO: add some kind of animation and remove the node
        }
    });

    searchInput.addEventListener('keyup', function (e) {
        var toSearch = this.value;

        if (toSearch === '') {
            removeAllChildren(resultsEl);
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
    });

    function removeAllChildren(parent) {
        while (parent.firstChild) {
            parent.removeChild(parent.firstChild);
        }
    }

    function searchCallback(err, content) {
        if (err) {
            console.error(err);
            return;
        } else {
            removeAllChildren(resultsEl);

            if (content.hits.length > 0) {
                var i;

                for (i = 0; i < content.hits.length; i++) {
                    resultsEl.appendChild(createResultRow(content.hits[i]));
                }
            }
        }

        function createResultRow(content) {
            var mainEl = resultTemplateContent.querySelector('.result');
            var nameEl = resultTemplateContent.querySelector('.name');
            var categoryEl = resultTemplateContent.querySelector('.category');
            var externalLinkEl = resultTemplateContent.querySelector('.externalLink');

            mainEl.dataset.id = content.objectID;

            // may contain em tags for highlights
            nameEl.innerHTML = content._highlightResult.name.value;
            nameEl.href = content.link;

            // may contain escaped chars
            categoryEl.innerHTML = content.category;

            return document.importNode(resultTemplateContent, true);
        }

        console.log(content);
    }

    function deleteEntity(id, isConfirmed) {
        // TODO: perhaps this confirmation thing could be done otherwise?
        if (isConfirmed === undefined) {
            deleteEntity(id, window.confirm('Are you sure you want to delete this object?'));
        } else if(isConfirmed) {
            // TODO: id is already a string, is this needed?
            fetch('/api/1/apps/' + id.toString(), {method: 'DELETE'})
            .then(function (response) {
                console.log(response);
            });
        }
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