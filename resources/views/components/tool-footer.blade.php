<script>
    const url = window.location;
    const queryURL = new URLSearchParams(url.search);
  
    let timeSpentSeconds = 0;
    let scrollTimeout;
    let isScrolling = false;
    
     
    function isPageVisible() {
      return document.visibilityState === 'visible';
    }

    window.addEventListener('scroll', () => {
      isScrolling = true;
      clearTimeout(scrollTimeout);
      scrollTimeout = setTimeout(() => {
        isScrolling = false;
      }, 10000);
    });

    function trackTime() {
      if (isPageVisible() && isScrolling) {
        timeSpentSeconds ++;
        navigator.sendBeacon(url.href, new URLSearchParams({
          time_spent: Math.ceil(timeSpentSeconds / 60)
        }));
      }
    }

    // Initialize scrolling state and timer
    isScrolling = true;
    scrollTimeout = setTimeout(() => { isScrolling = false; }, 10000);

    setInterval(trackTime, 1000);

    window.addEventListener('beforeunload', function() {
      if (timeSpentSeconds > 0) {
          navigator.sendBeacon(url.href, new URLSearchParams({
          time_spent: Math.ceil(timeSpentSeconds / 60)
        }));
      }
    });
 
 
  </script>