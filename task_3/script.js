(function () {
    // Функция для получения IP адреса
    function getIP() {
        return fetch('https://api.ipify.org?format=json')
            .then(response => response.json())
            .then(data => data.ip);
    }

    // Функция для получения геолокации по IP
    function getGeoInfo(ip) {
        return fetch(`http://ip-api.com/json/${ip}`)
            .then(response => response.json());
    }

    // Функция для получения информации об устройстве
    function getDeviceInfo() {
        const ua = navigator.userAgent;
        let device = "Unknown";

        if (/mobile/i.test(ua)) {
            device = "Mobile";
        } else if (/tablet/i.test(ua)) {
            device = "Tablet";
        } else {
            device = "Desktop";
        }

        return device;
    }

    // Функция для отправки данных на сервер
    function sendData(data) {
        fetch('https://your-domain.com/collect.php', { // Замените на ваш URL
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        }).catch(error => console.error('Error sending data:', error));
    }

    // Основная функция
    async function trackVisitor() {
        try {
            const ip = await getIP();
            const geo = await getGeoInfo(ip);
            const device = getDeviceInfo();

            const data = {
                ip: ip,
                city: geo.city,
                country: geo.country,
                device: device,
                timestamp: new Date().toISOString()
            };

            sendData(data);
        } catch (error) {
            console.error('Tracking Error:', error);
        }
    }

    // Запуск отслеживания при загрузке страницы
    window.onload = trackVisitor;
})();