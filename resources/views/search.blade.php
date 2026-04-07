<x-main>
    @include('components/head')
    @include('components/header')


<body>
    <div id="search-page" class="page with-sidebars-page with-single-sidebar-page">
        

        <div class="page-details page-details-single-sidebar">

            <div class="search-page-search-container">
              
               <div style="display: flex; gap:10px;">
                    <input type="search" id="search-page-query"  placeholder="Type to search...">
                </div>
                <input type="search" id="query-in" hidden>
 
            </div>
            <br>
            <div id="search-results-filter">
                
                    <small style="margin-right: 10px;">Seached in: </small>

                    
                    
                    <a class="link-tag-button filter-button" id="filter-teacher-files-button">TEACHER FILES</a>
                  
                    <small id="filter-teacher-files-indicator" class="indicator">TEACHER FILES</small>
                    

                    <a class="link-tag-button filter-button" id="filter-articles-button">ARTICLES</a>
                  
                    <small id="filter-articles-indicator" class="indicator">ARTICLES</small>
                   

                    
                    <a class="link-tag-button filter-button" id="filter-researches-button">RESEARCHES</a>
                  
                    <small id="filter-researches-indicator" class="indicator">RESEARCHES</small>


                    <a class="link-tag-button filter-button" id="filter-tools-button">TOOLS</a>
                  
                    <small id="filter-tools-indicator" class="indicator">TOOLS</small>


                    <small>|</small>
                    

                    <a class="link-tag-button filter-button" id="filter-accounts-button">ACCOUNTS</a>
                  
                    <small id="filter-accounts-indicator" class="indicator">ACCOUNTS</small>
                
                


            </div>

            <hr>
            <div id="search-results">

            </div>

            

            

        </div>
    
        @include('components/website-sidebar')      


    </div>


@include('components/footer')
</body>
</x-main>