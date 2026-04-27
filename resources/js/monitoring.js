import axios from "axios";

window.domainCheck = async function domainCheck() {
    axios.get('/dashboard/check')
        .then(response => {
            const domains = Object.values(response.data);

            domains.forEach(domain => {
                const element = document.getElementById(domain.domain);

                if (!element) {
                    console.warn('Element not found:', domain.domain);
                    return;
                }

                element.classList.remove('bg-success', 'bg-danger', 'bg-warning');

                if (domain.http_code === 200) {
                    element.classList.add('bg-success');
                } else {
                    element.classList.add('bg-danger');
                }
            });
        })
        .catch(error => {
            console.log(error);
        });
};
