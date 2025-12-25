document.addEventListener('DOMContentLoaded', function(){
    const count_btn = document.getElementById('lookup');
    const city_btn = document.getElementById('lookcity')
    const result = document.getElementById('result');
    const input = document.getElementById('country');

    function perform_lookup(type){
    
         
        let country = input.value.trim();
        let url = 'world.php';
        let xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest object
        let params = [];

        if (country) {
            params.push('country=' + encodeURIComponent(country));
        }

        if (type === 'cities'){
            params.push('lookup=cities')
        }

        if (params.length > 0) {
            url += '?' + params.join('&'); // join strings with & in between if type = cities 
        } 

        xhr.open('GET', url, true);

        // Set up the callback function for when the request completes
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                // Request was successful
                result.innerHTML = xhr.responseText;
            } else {
                // Request failed
                result.innerHTML = '<p>Error fetching data. Please try again.</p>';
            }
        };

        xhr.send();

    }
    count_btn.addEventListener('click', function(e){
        e.preventDefault();
        perform_lookup(''); // empty string for default country use
    });

    city_btn.addEventListener('click', function(e){
        e.preventDefault();
        perform_lookup('cities'); // cities string for city lookup
    });


});