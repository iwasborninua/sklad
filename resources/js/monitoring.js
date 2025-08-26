import axios from "axios";

window.domainCheck = async function domainCheck(resources) {
    resources.forEach(domain => {
        axios.get('/dashboard/check', { params: { domain: domain } })
            .then(response => {
                console.log(domain, response.data);
                let element = document.getElementById(domain);
                let status = element.querySelector('.status-code');
                status.textContent = response.data.statusCode;
                element.classList.add(response.data.class);
            })
            .catch(error => {
                console.log('ошибОЧКА: ' + domain);
                let element = document.getElementById(domain);
                let status = element.querySelector('.status-code');
                status.textContent = 'Запрос не удался';
                element.classList.add('bg-warning');
                // console.error('Error checking domain:', domain, error);
            });
    });
};
