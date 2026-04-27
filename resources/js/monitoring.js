import axios from "axios";
import data from "bootstrap/js/src/dom/data.js";

window.domainCheck = async function domainCheck(resources) {
    axios.get('/dashboard/check')
        .then(response => {
            response.data.forEach(domain => {
                if (domain['http_code'] == 200) {
                    document.getElementById(domain['domain']).classList.add('bg-success')
                } else {
                    document.getElementById(domain['domain']).classList.add('bg-danger')
                }
            })
        })
        .catch(error => {
            console.log(error)
    })
};
