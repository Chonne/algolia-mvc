/**
 * AlgoliaApp, simply creating its own scope
 * TODO: this could be done in other ways, with separate modules (input form, add form, algolia related code)
 */
const AlgoliaApp = (function() {
    const resultsPerPage = 5;

    let searchClient = null;
    let index = null;

    let msgContainer = null;
    let msgContentEl = null;
    let searchInput = null;
    let addButton = null;
    let addForm = null;
    let addFormTextarea = null;
    let resultsEl = null;
    let resultTemplateContent = null;

    function initApp(config) {
        initElements();

        initSearchClient(config);

        initEvents();
    }

    function initSearchClient(config) {
        try {
            searchClient = algoliasearch(config.algolia_applicationID, config.algolia_apiKey);
            index = searchClient.initIndex(config.algolia_indexName);
            enableApp();
        } catch (err) {
            updateMsg(err.name + ': ' + err.message, 'error');
        }
    }

    function initElements() {
        msgContainer = document.getElementById('msg');
        msgContentEl = msgContainer.querySelector('.content');
        searchInput = document.getElementById('searchInput');
        addButton = document.getElementById('addButton');
        addForm = document.getElementById('addForm');
        addFormTextarea = addForm.querySelector('textarea');
        resultsEl = document.getElementById('results');
        resultTemplateContent = document.getElementById('resultTpl').content;
    }

    function initEvents() {
        // Message box hide button
        msgContainer.querySelector('.hide').addEventListener('click', function (e) {
            msgContainer.classList.add('hidden');
        });

        // Click event on the delete buttons
        resultsEl.addEventListener('click', function (e) {
            // Not sure this is the best way to do this
            if (e.target.dataset.deleteEntity === 'true') {
                const objectID = e.target.parentElement.dataset.id;
                deleteEntity(objectID);

                // TODO: add some kind of animation and remove the node
            }
        });

        // Pressing keys in the search input
        searchInput.addEventListener('keyup', function (e) {
            const toSearch = this.value;
            e.stopPropagation();

            if (toSearch === '') {
                removeAllChildren(resultsEl);
            } else {
                // the last optional argument can be used to add search parameters
                index.search(
                    toSearch, {
                        hitsPerPage: resultsPerPage,
                        facets: '*',
                        maxValuesPerFacet: 10
                    },
                    searchCallback
                );
            }
        });

        // TODO: do something better, this is only to test
        addButton.addEventListener('click', showAddForm);

        document.addEventListener('keyup', function (e) {
            if (e.key === '/' && !e.ctrlKey && !e.altKey) {
                searchInput.select();
            }

            if (e.key === 'n' && e.ctrlKey && !e.altKey) {
                showAddForm();
            }
        });

        initAddFormEvents();
    }

    function initAddFormEvents() {
        const addFormCancelButton = addForm.querySelector('button.cancel');

        addFormCancelButton.addEventListener('click', hideAddForm);

        addForm.addEventListener('submit', function (e) {
            e.preventDefault();

            addEntity(addFormTextarea.value)
            .then(function (response) {
                // TODO: check for response.ok instead?
                if (response.status !== 201) {
                    return response.text().then(function (response) {
                        throw response;
                    });
                }

                hideAddForm();
            }).catch(function (error) {
                updateMsg(error, 'error');
            });
        });
    }

    function enableApp() {
        searchInput.disabled = false;
        addButton.disabled = false;
    }

    function showAddForm() {
        if (!addButton.disabled) {
            addForm.classList.remove('hidden');
            addFormTextarea.select();
        }
    }

    function hideAddForm() {
        addForm.classList.add('hidden');
        addForm.reset();
    }

    function removeAllChildren(parent) {
        while (parent.firstChild) {
            parent.removeChild(parent.firstChild);
        }
    }

    function updateMsg(msg, type) {
        const types = [
            'info',
            // 'warning',
            'error',
        ];

        if (types.indexOf(type) === -1) {
            type = types[0];
        }

        // Removing obsolete classes
        for (let i = 0; i < types.length; i++) {
            if (type !== types[i] && msgContainer.classList.contains(types[i])) {
                msgContainer.classList.remove(types[i]);
            }
        }

        msgContentEl.innerText = msg;
        msgContainer.classList.add(type);
        msgContainer.classList.remove('hidden');
        console[type](msg);
    }

    function createResultRow(content) {
        let mainEl = resultTemplateContent.querySelector('.result');
        let nameEl = resultTemplateContent.querySelector('.name');
        let categoryEl = resultTemplateContent.querySelector('.category');

        mainEl.dataset.id = content.objectID;

        // name and category may contain em tags for highlights and escaped chars
        nameEl.innerHTML = content._highlightResult.name.value;
        nameEl.href = content.link;

        categoryEl.innerHTML = content._highlightResult.category.value;

        return document.importNode(resultTemplateContent, true);
    }

    function searchCallback(err, content) {
        if (err) {
            updateMsg(error, 'error');
            return;
        } else {
            removeAllChildren(resultsEl);

            if (content.hits.length > 0) {
                for (let i = 0; i < content.hits.length; i++) {
                    resultsEl.appendChild(createResultRow(content.hits[i]));
                }
            }
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
        let data = new FormData();
        data.append('data', json);

        return fetch('/api/1/apps', {
            method: 'POST',
            body: data,
        });
    }

    return {
        /**
         * Initializes the "module"
         * @param  {object} config Configuration for the Algolia search client
         */
        init: function(config) {
            initApp(config);
        }
    };
}());
