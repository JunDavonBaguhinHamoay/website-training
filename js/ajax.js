function loadContent(page) {
    var xhttp = new XMLHttpRequest();
    
    // Show loading message in #content div
    document.getElementById("content").innerHTML = "Loading...";

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            if (this.status == 200) {
                // Success: inject content into #content
                document.getElementById("content").innerHTML = this.responseText;
            } else {
                // Error: show an error message
                document.getElementById("content").innerHTML = "Oops! Something went wrong.";
                console.error('Error: ' + this.statusText);
            }
        }
    };
    
    xhttp.open("GET", `${page}.php`, true);
    xhttp.send();
}
