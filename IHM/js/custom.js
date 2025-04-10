// Get Current Year
function getCurrentYear() {
    var d = new Date();
    var year = d.getFullYear();
    document.querySelector("#displayDateYear").innerText = year;
}
getCurrentYear()

//client section owl carousel
$(".owl-carousel").owlCarousel({
    loop: true,
    margin: 10,
    nav: true,
    dots: false,
    navText: [
        '<i class="fa fa-long-arrow-left" aria-hidden="true"></i>',
        '<i class="fa fa-long-arrow-right" aria-hidden="true"></i>'
    ],
    autoplay: true,
    autoplayHoverPause: true,
    responsive: {
        0: {
            items: 1
        },
        768: {
            items: 2
        },
        1000: {
            items: 2
        }
    }
});

/** google_map js **/

function myMap() {
    var mapProp = {
        center: new google.maps.LatLng(40.712775, -74.005973),
        zoom: 18,
    };
    var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
}

function toggleFields() {
    const type = document.getElementById("type").value;
    const compteur = document.getElementById("numero_compteur");
    const adresse = document.getElementById("adresse_installation");

    const isFournisseur = type === "fournisseur";

    compteur.disabled = isFournisseur;
    adresse.disabled = isFournisseur;

    // Pour ne pas envoyer ces valeurs s'ils sont désactivés
    if (isFournisseur) {
        compteur.removeAttribute("required");
        adresse.removeAttribute("required");
    } else {
        compteur.setAttribute("required", "required");
        adresse.setAttribute("required", "required");
    }
}

// Exécuter une première fois si le type est déjà sélectionné
document.addEventListener("DOMContentLoaded", () => {
    toggleFields();
});