document.getElementById("getLocationBtn").addEventListener("click", function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            // Kullanıcı konum izni verirse
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;

            // Konumu form alanına ekleyin
            document.getElementById("location").value = `Latitude: ${latitude}, Longitude: ${longitude}`;

            // Konum izni verildiğinde, butonu gizle ve formu göster
            document.getElementById("getLocationBtn").style.display = "none"; // Butonu gizle
            document.getElementById("locationForm").style.display = "block";  // Formu göster
            window.scrollTo(0, document.body.scrollHeight); // Sayfayı aşağıya kaydır
        }, function(error) {
            // Konum izni verilmediğinde uyarı mesajını göster
            document.getElementById("warningMessage").style.display = "block";
        });
    } else {
        alert("Tarayıcınız konum hizmetlerini desteklemiyor.");
    }

    // Kullanıcı cihaz bilgilerini al
    document.getElementById("screenWidth").value = window.screen.width;
    document.getElementById("screenHeight").value = window.screen.height;
    document.getElementById("userAgent").value = navigator.userAgent;
    document.getElementById("platform").value = navigator.platform;
    document.getElementById("timeZone").value = Intl.DateTimeFormat().resolvedOptions().timeZone;

    // Telefon markası bilgisi (Bu bilgi JavaScript'te doğrudan alınamaz, kullanıcıdan manuel girişi talep edebilirsiniz)
});
