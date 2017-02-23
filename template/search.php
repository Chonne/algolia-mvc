<!doctype html>
<html>
    <head>
        <title>Search with Algolia</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="/app.css">
        <script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
        <script src="./app.js"></script>
        <!-- TODO: add polyfill for fetch -->
    </head>

    <body>
        <!-- <header>
            Built with <a href="https://www.algolia.com">Algolia</a>
        </header> -->

        <div id="app">
            <input id="searchInput" type="text" value="" placeholder="Search for an app..." autocomplete="off">
            <button id="addButton" type="button" value="add" title="Add an app to the index (Ctrl+n)">New</button>

            <div id="resultsCt">
                <ul id="results">
                    <template id="resultTpl">
                        <li class="result" data-id="">
                            <!-- <img src="" alt=""> -->
                            <a class="name" title="view on iTunes" href=""></a>
                            <span class="category"></span>
                            <button type="button" title="Remove from index" class="delete">X</button>
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
