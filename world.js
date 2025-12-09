document.addEventListener('DOMContentLoaded', function(){
    const button = document.getElementById('lookup');
    const result = document.getElementById('result');
    const input = document.getElementById('country');
    
    button.addEventListener('click', function(e){
        e.preventDefault(); 
        let country = input.value.trim();
        let url = 'world.php';
        let xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest object

        if (country) {
            url += '?country=' + encodeURIComponent(country);
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






    });

});