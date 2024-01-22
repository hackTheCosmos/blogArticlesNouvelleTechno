//HEADER-------------------------------------------------------------------

// Menu burger (version mobile et tablet)---------------------

    const nav = document.querySelector("nav");
    const navClass = nav.classList;

    const burgerIcon = document.querySelector(".burger__icon");
    let burgerIconClass = burgerIcon.classList;
    
    // affiche/cache navigation, et anime l'icone du menu burger
    burgerIcon.addEventListener("click", () => {
        navClass.toggle("display__nav");
        burgerIconClass.toggle("fa-bars");
        burgerIconClass.toggle("fa-xmark")
    });