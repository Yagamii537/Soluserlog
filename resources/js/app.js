import "bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";
// resources/js/app.js
import "@fortawesome/fontawesome-free/css/all.min.css";

import Alpine from "alpinejs";
import focus from "@alpinejs/focus";
window.Alpine = Alpine;

Alpine.plugin(focus);

Alpine.start();
