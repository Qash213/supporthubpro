self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    var link = event.notification.data.link;
    event.waitUntil(
        clients.openWindow(link)
    )
});
