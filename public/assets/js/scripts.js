  class MenuItem {
    constructor(element) {
        this.element = element;
        this.subMenu = element.nextElementSibling;
        this.parentMenu = element.closest(".dropdown-menu");
        this.subMenus = this.subMenu.querySelectorAll(".dropdown-menu");
        this.initialize();
    }

    initialize() {
        this.element.addEventListener("click", (event) => {
            event.stopPropagation();
            this.showSubMenu();
        });
    }

    showSubMenu() {
        this.parentMenu.querySelectorAll(".dropdown-menu").forEach(subMenu => {
            if (subMenu !== this.subMenu) {
                subMenu.style.visibility = "hidden";
            }
        });

        this.subMenus.forEach(subMenu => {
            subMenu.style.visibility = "hidden";
        });

        this.subMenu.style.visibility = "visible";
    }
}

const menuItems = document.querySelectorAll('.dropdown-menu .dropdown-toggle[href="#"]');
const menuElements = Array.from(menuItems).map(element => new MenuItem(element));
