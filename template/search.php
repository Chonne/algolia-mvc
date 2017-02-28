<!doctype html>
<html>
    <head>
        <title>Search with Algolia</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="/css/app.css">
        <script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
        <script src="/js/lib/polyfill-fetch.js"></script>
        <script src="/js/app.js"></script>
        <!-- TODO: add polyfill for fetch -->
    </head>

    <body>
        <header>
            <h1>
                App store search engine
            </h1>
            <span class="copyright">
                Engine powered by <a href="https://www.algolia.com">Algolia</a>, Icons by <a href="https://icons8.com">Icons8</a>, <a href="https://github.com/Chonne/algolia-mvc" title="See the code on GitHub">fork this</a>
            </span>
        </header>

        <div id="app">
            <div id="msg" class="hidden">
                <span class="content"></span>
                <button type="button" class="hide" title="Close message"><i class="icons8-delete-2"></i></button>
            </div>

            <i class="icons8-search"></i>
            <input disabled id="searchInput" type="text" value="" placeholder="Search for an app..." autocomplete="off" title="Search by name or category (keyboard shortcut: '/')">
            <button disabled id="addButton" type="button" value="add" title="Add an app to the index (Ctrl+n)"><i class="icons8-plus"></i>New</button>

            <div id="resultsCt">
                <ul id="results">
                    <template id="resultTpl">
                        <li class="result" data-id="">
                            <!-- <img src="" alt=""> -->
                            <a class="name" title="view on iTunes" href=""></a>
                            <span class="category"></span>
                            <button type="button" title="Remove from index" class="delete icons8-trash" data-delete-entity="true"></button>
                        </li>
                    </template>
                </ul>
            </div>

            <form id="addForm" class="hidden">
                <header>
                    Add an app
                </header>
                <textarea>
{
    "name": "",
    "image": "",
    "link": "",
    "category": "",
    "rank": 1
}</textarea>
                <footer>
                    <button type="submit" class="submit">Add</button>
                    <button type="button" class="cancel">Cancel</button>
                </footer>
            </form>
        </div>

        <script>
            AlgoliaApp.init({
                algolia_applicationID: '<?php echo $templateParams['algolia_applicationID'] ?>',
                algolia_apiKey: '<?php echo $templateParams['algolia_apiKey'] ?>',
                algolia_indexName: '<?php echo $templateParams['algolia_indexName'] ?>',
            });
        </script>
    </body>
</html>
