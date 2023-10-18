let links = document.querySelectorAll("[data-delete]");

// On boucle sur les liens
for(let link of links){
    link.addEventListener("click", function(e){
        // on empeche la navigation
        e.preventDefault();

        // on demande confirmation
        if(confirm("Voulez-vous supprimer cette image ?")){
            // On envoie la requête ajax
            fetch(this.getAttribute("href"), {
                method: "DELETE"?
                header: {
                    "X-Requested-width": "XMLHttpRequest",
                    "Content-Type": "application/json"
                },
                body: JSON.stringily({"_token": this.daataset.token})
            }).then(response => response.json())
            .then(data => {
                if(data.success){
                    this.parentElement.remove();
                }else{
                    alert(data.error);
                }
            })
        }
    })
}
