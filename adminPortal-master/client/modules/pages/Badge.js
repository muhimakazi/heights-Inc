/**
 * BADGE MODULE
 * Page: /badge
 */
 import * as General from "../../public/general.js";
 import * as HTTP from "../../core/http.js";
 import * as Security from "../../core/Security.js";


 // EVENT LISTENERS
 $(document).ready(function(){
    window.setTimeout(function(){
        General.printPageArea("printable");
    }, 2000);
 });