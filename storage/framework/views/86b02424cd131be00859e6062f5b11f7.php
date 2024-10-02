<!-- Back to top -->
<a href="#top" id="back-to-top"><span class="feather feather-chevrons-up"></span></a>

<!-- Bootstrap js-->
<script src="<?php echo e(asset('build/assets/plugins/bootstrap/popper.min.js')); ?>?v=<?php echo time(); ?>"></script>
<script src="<?php echo e(asset('build/assets/plugins/bootstrap/js/bootstrap.min.js')); ?>?v=<?php echo time(); ?>"></script>

<!--Sidemenu js-->
<script src="<?php echo e(asset('build/assets/plugins/sidemenu/sidemenu.js')); ?>?v=<?php echo time(); ?>"></script>

<!-- P-scroll js-->
<script src="<?php echo e(asset('build/assets/plugins/p-scrollbar/p-scrollbar.js')); ?>?v=<?php echo time(); ?>"></script>
<script src="<?php echo e(asset('build/assets/plugins/p-scrollbar/p-scroll1.js')); ?>?v=<?php echo time(); ?>"></script>

<!-- Select2 js -->
<script src="<?php echo e(asset('build/assets/plugins/select2/select2.full.min.js')); ?>?v=<?php echo time(); ?>"></script>

<!--INTERNAL RATING js -->
<script src="<?php echo e(asset('build/assets/plugins/ratings/jquerystarrating.js')); ?>?v=<?php echo time(); ?>"></script>

<!--INTERNAL Toastr js -->
<script src="<?php echo e(asset('build/assets/plugins/toastr/toastr.min.js')); ?>?v=<?php echo time(); ?>"></script>

<?php echo $__env->yieldContent('scripts'); ?>

<!-- Custom html js-->
<?php echo app('Illuminate\Foundation\Vite')(['resources/assets/js/custom.js']); ?>
<?php if(setting('liveChatHidden') == "false"): ?>
    <script domainName='<?php echo e(url('')); ?>' wsPort="<?php echo e(setting('liveChatPort')); ?>"
        src="<?php echo e(asset('build/assets/plugins/livechat/web-socket.js')); ?>?v=<?php echo time(); ?>"></script>
    <script>
        // Increment the count of open tabs/windows when a new one is opened
        let loadedEventTriger = false
            window.addEventListener('load', function() {
                loadedEventTriger = true
                // To check the tabs Open
                let tabsOpen = localStorage.getItem('tabsOpen');
                tabsOpen = tabsOpen ? parseInt(tabsOpen) + 1 : 1;
                localStorage.setItem('tabsOpen', tabsOpen.toString());
            });


            let OpenUserInfo = []

            // To Romove the last user from the Setting
            function beforeUnloadHandler(e) {
                if (loadedEventTriger) {
                    let tabsOpen = localStorage.getItem('tabsOpen');
                    if (tabsOpen) {
                        tabsOpen = parseInt(tabsOpen) - 1;
                        if (tabsOpen <= 0) {
                            let data = {
                                users: "",
                                onlineUserUpdated: true
                            }
                            $.ajax({
                                type: "post",
                                url: '<?php echo e(route('admin.onlineUsersSave')); ?>',
                                data: data,
                                success: function(data) {

                                },
                                error: function(data) {
                                    console.log('Error:', data);
                                }
                            });
                            localStorage.removeItem('tabsOpen');
                        }
                        // localStorage.setItem('tabsOpen', tabsOpen.toString());
                    }
                }
            }

            //LiveChat  Online users induction logic
            let onlineUsersFindChannel = Echo.join('agentMessage')
            onlineUsersFindChannel.here((users) => {
                console.log('Here',users);
                let data = {
                    users: JSON.stringify(users),
                    onlineUserUpdated: true
                }

                $.ajax({
                    type: "post",
                    url: '<?php echo e(route('admin.onlineUsersSave')); ?>',
                    data: data,
                    success: function(data) {

                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });

                // adding the window close alert
                if (users.length == 1 && users[0].id == "<?php echo e(Auth::user()->id); ?>") {
                    OpenUserInfo = users
                    window.addEventListener('beforeunload', beforeUnloadHandler);
                }
            })
            onlineUsersFindChannel.joining((user) => {
                let data = {
                    users: JSON.stringify(Object.values(onlineUsersFindChannel.subscription.members
                        .members)),
                    onlineUserUpdated: true
                }
                $.ajax({
                    type: "post",
                    url: '<?php echo e(route('admin.onlineUsersSave')); ?>',
                    data: data,
                    success: function(data) {

                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });

                // removing the window close alert
                if (JSON.parse(data.users).length > 1) {
                    window.removeEventListener('beforeunload', beforeUnloadHandler);
                }
            })
            onlineUsersFindChannel.leaving((user) => {
                let data = {
                    users: JSON.stringify(Object.values(onlineUsersFindChannel.subscription.members
                        .members)),
                    onlineUserUpdated: true
                }
                $.ajax({
                    type: "post",
                    url: '<?php echo e(route('admin.onlineUsersSave')); ?>',
                    data: data,
                    success: function(data) {

                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });

                // removing the window close alert
                if (JSON.parse(data.users).length > 1) {
                    window.removeEventListener('beforeunload', beforeUnloadHandler);
                } else {
                    OpenUserInfo = JSON.parse(data.users)
                    window.addEventListener('beforeunload', beforeUnloadHandler);
                }
            })

            // Variables
            var SITEURL = '<?php echo e(url('')); ?>';
            var notificationsSounds = "<?php echo e(setting('notificationsSounds')); ?>"
            var newMessageWebNot = "<?php echo e(setting('newMessageWebNot')); ?>"
            var newMessageSound = "<?php echo e(setting('newMessageSound')); ?>"
            var newChatRequestSound = "<?php echo e(setting('newChatRequestSound')); ?>"
            var newChatRequestWebNot = "<?php echo e(setting('newChatRequestWebNot')); ?>"
            var liveChatCustomers = <?php echo json_encode(liveChatCustomers()); ?>

            const autID_G = '<?php echo e(Auth::user()->id); ?>'
            var notificationType = "<?php echo e(setting('notificationType')); ?>"

            // Live Chat Global Web Notifications
            window.newMessageSoundCurrentAudio = "";
            let intervalId

            // For the Live Chat Notification
            Echo.channel('liveChat').listen('ChatMessageEvent', (socket) => {

                // For the New Chat request
                if (socket.message == "newUser" && parseInt(notificationsSounds) && parseInt(
                        newChatRequestWebNot)) {
                    // For the Window notification
                    if ("Notification" in window) {
                        if (Notification.permission === "granted") {
                            notify();
                        } else {
                            Notification.requestPermission().then(res => {
                                if (res === "granted") {
                                    notify();
                                } else {
                                    console.error("Did not receive permission for notifications");
                                }
                            })
                        }
                    } else {
                        console.error("Browser does not support notifications");
                    }

                    function notify() {
                        navigator.serviceWorker.ready.then(function(registration) {
                            registration.showNotification(socket.userName, {
                                body: socket.message,
                                icon: `${SITEURL}/uploads/profile/user-profile.png`,
                                vibration: 5000,
                                data: {
                                    link: `${SITEURL}/admin/livechat`
                                }
                            });
                        })

                    }

                    // Stop the current audio if it exists
                    if (newMessageSoundCurrentAudio) {
                        newMessageSoundCurrentAudio.pause();
                        newMessageSoundCurrentAudio.currentTime = 0;
                    }

                    // Create a new audio element
                    let audioElement = document.createElement('audio');
                    audioElement.id = "audioPlayer";
                    audioElement.innerHTML = `
                        <source src="<?php echo e(url('')); ?>/assets/sounds/${newChatRequestSound}">
                    `;

                    // Play the new audio
                    if (audioElement.paused) {
                        audioElement.play();
                    }
                    newMessageSoundCurrentAudio = audioElement;
                    newMessageSoundCurrentAudio.__proto__.cusumerId = socket.id


                    // Function to check localStorage value and play or stop the audio
                    if (notificationType == "Loop") {
                        function checkAndPlaySound() {
                            if (localStorage.livechatCustomer == newMessageSoundCurrentAudio.__proto__
                                .cusumerId) {
                                if (!audioElement.paused) {
                                    audioElement.pause();
                                }
                                clearInterval(intervalId);
                            } else {
                                if (audioElement.paused) {
                                    audioElement.play();
                                }
                            }
                        }

                        let intervalId = setInterval(checkAndPlaySound, 1000);
                    }
                }

                // For the New message
                if (parseInt(notificationsSounds) && parseInt(newMessageWebNot)) {
                    liveChatCustomers.forEach((ele) => {
                        if (ele.id == socket.id && !socket.agentInfo) {
                            try {
                                if (ele.engage_conversation == null && socket.message || (JSON.parse(ele.engage_conversation) && JSON.parse(ele.engage_conversation).some(item => item.id == autID_G))) {

                                    if ("Notification" in window) {
                                        if (Notification.permission === "granted") {
                                            notify();
                                        } else {
                                            Notification.requestPermission().then(res => {
                                                if (res === "granted") {
                                                    notify();
                                                } else {
                                                    console.error(
                                                        "Did not receive permission for notifications"
                                                    );
                                                }
                                            })
                                        }
                                    } else {
                                        console.error("Browser does not support notifications");
                                    }

                                    function notify() {
                                        navigator.serviceWorker.ready.then(function(registration) {
                                            registration.showNotification(socket.userName, {
                                                body: socket.message,
                                                icon: `${SITEURL}/uploads/profile/user-profile.png`,
                                                vibration: 5000,
                                                data: {
                                                    link: `${SITEURL}/admin/myopened`
                                                }
                                            });
                                        })
                                    }

                                    // Stop the current audio if it exists
                                    if (newMessageSoundCurrentAudio) {
                                        newMessageSoundCurrentAudio.pause();
                                        newMessageSoundCurrentAudio.currentTime = 0;
                                    }

                                    // Create a new audio element
                                    let audioElement = document.createElement('audio');
                                    audioElement.id = "audioPlayer";
                                    audioElement.innerHTML = `
                                            <source src="<?php echo e(url('')); ?>/build/assets/sounds/${newMessageSound}">
                                        `;

                                    // Play the new audio
                                    if (audioElement.paused) {
                                        audioElement.play();
                                    }

                                    // Set the new audio as the current audio
                                    newMessageSoundCurrentAudio = audioElement;
                                    newMessageSoundCurrentAudio.__proto__.cusumerId = socket.id

                                    // To remove the Sound the chatBody click
                                    if (notificationType == "Loop") {
                                        const clickEventHandler = () => {
                                            newMessageSoundCurrentAudio.pause();
                                            clearInterval(intervalId);
                                            document.querySelector(
                                                    `#operator-conversation-Info[data-id="${socket.id}"]`
                                                ).closest('.main-chat-area')
                                                .removeEventListener('click', clickEventHandler);
                                            intervalId = null
                                        };
                                        if (document.querySelector(
                                                `#operator-conversation-Info[data-id="${socket.id}"]`
                                            )) {
                                            document.querySelector(
                                                `#operator-conversation-Info[data-id="${socket.id}"]`
                                            ).closest('.main-chat-area').addEventListener(
                                                'click', clickEventHandler);
                                        }

                                        function checkAndPlaySound() {
                                            if (audioElement.paused) {
                                                audioElement.play();
                                            }
                                        }

                                        if (!intervalId) {
                                            intervalId = setInterval(checkAndPlaySound, 1000);
                                        }
                                    }
                                }
                            } catch (error) {
                                console.log("engage conversation not getting array");
                            }
                        }
                    })
                }
            })


            let operatorsNotificationsSounds = "<?php echo e(setting('operatorsNotificationsSounds')); ?>"
            let operatorsAgentToAgentWebNot = "<?php echo e(setting('operatorsAgentToAgentWebNot')); ?>"
            let operatorsAgentToAgentSound = "<?php echo e(setting('operatorsAgentToAgentSound')); ?>"
            let operatorsGroupChatWebNot = "<?php echo e(setting('operatorsGroupChatWebNot')); ?>"
            let operatorsGroupChatSound = "<?php echo e(setting('operatorsGroupChatSound')); ?>"


            // For the Operators Notifications
            onlineUsersFindChannel.listen('AgentMessageEvent', (socket) => {

                // For the Agent To Agent Chat
                if (autID_G == socket.receiverId && !socket.groupInclude && socket.message &&
                    operatorsAgentToAgentSound && parseInt(operatorsAgentToAgentWebNot) && parseInt(
                        operatorsNotificationsSounds)) {
                    let allUsers = '<?php echo json_encode(\App\Models\User::get()); ?>'
                    let SenderImage = JSON.parse(allUsers).filter((arr) => parseInt(arr.id) == parseInt(
                        socket.senderId))[0].image

                    // For the Window notification
                    if ("Notification" in window) {
                        if (Notification.permission === "granted") {
                            notify();
                        } else {
                            Notification.requestPermission().then(res => {
                                if (res === "granted") {
                                    notify();
                                } else {
                                    console.error("Did not receive permission for notifications");
                                }
                            })
                        }
                    } else {
                        console.error("Browser does not support notifications");
                    }

                    function notify() {
                        navigator.serviceWorker.ready.then(function(registration) {
                            registration.showNotification(socket.senderName, {
                                body: socket.message,
                                icon: SenderImage ?
                                    `${SITEURL}/uploads/profile/${SenderImage}` :
                                    `${SITEURL}/uploads/profile/user-profile.png`,
                                vibration: 5000,
                                data: {
                                    link: `${SITEURL}/admin/operators`
                                }
                            });
                        })
                    }

                    // Stop the current audio if it exists
                    if (newMessageSoundCurrentAudio) {
                        newMessageSoundCurrentAudio.pause();
                        newMessageSoundCurrentAudio.currentTime = 0;
                    }

                    // Create a new audio element
                    let audioElement = document.createElement('audio');
                    audioElement.id = "audioPlayer";
                    audioElement.innerHTML = `
                        <source src="<?php echo e(url('')); ?>/assets/sounds/${operatorsAgentToAgentSound}">
                    `;

                    // Play the new audio
                    if (audioElement.paused) {
                        audioElement.play();
                    }
                    newMessageSoundCurrentAudio = audioElement;

                }


                // For the Grop messages
                if (socket.groupInclude && JSON.parse(socket.groupInclude).includes(parseInt(autID_G)) &&
                    socket.message && operatorsGroupChatSound && parseInt(operatorsGroupChatWebNot) &&
                    parseInt(operatorsNotificationsSounds) && socket.senderId != parseInt(autID_G)) {

                    // For the Window notification
                    if ("Notification" in window) {
                        if (Notification.permission === "granted") {
                            notify();
                        } else {
                            Notification.requestPermission().then(res => {
                                if (res === "granted") {
                                    notify();
                                } else {
                                    console.error("Did not receive permission for notifications");
                                }
                            })
                        }
                    } else {
                        console.error("Browser does not support notifications");
                    }

                    function notify() {
                        navigator.serviceWorker.ready.then(function(registration) {
                            registration.showNotification(socket.senderName, {
                                body: socket.message,
                                icon: `${SITEURL}/uploads/profile/group.png`,
                                vibration: 5000,
                                data: {
                                    link: `${SITEURL}/admin/operators`
                                }
                            });
                        })
                    }

                    // Stop the current audio if it exists
                    if (newMessageSoundCurrentAudio) {
                        newMessageSoundCurrentAudio.pause();
                        newMessageSoundCurrentAudio.currentTime = 0;
                    }

                    // Create a new audio element
                    let audioElement = document.createElement('audio');
                    audioElement.id = "audioPlayer";
                    audioElement.innerHTML = `
                        <source src="<?php echo e(url('')); ?>/assets/sounds/${operatorsGroupChatSound}">
                    `;

                    // Play the new audio
                    if (audioElement.paused) {
                        audioElement.play();
                    }
                    newMessageSoundCurrentAudio = audioElement;
                }
            })
    </script>
<?php endif; ?>
<script type="text/javascript">

    $(function() {
        "use strict";

        // var domainName='<?php echo e(url('')); ?>';
        // var wsPort="<?php echo e(setting('liveChatPort')); ?>";


        <?php echo customcssjs('CUSTOMJS') ?>

        // Profile Rating
        $(".allprofilerating").starRating({
            readOnly: true,
            starSize: 20,
            emptyColor: '#17263a',
            activeColor: '#F2B827',
            strokeColor: '#556a86',
            strokeWidth: 15,
            useGradient: false
        });

        <?php if(auth()->user()): ?>

            //  Mark As Read
            function sendMarkRequest(id = null) {
                return $.ajax("<?php echo e(route('admin.markNotification')); ?>", {
                    method: 'GET',
                    data: {
                        // _token,
                        id
                    }
                });
            }
            (function($) {

                $('.mark-as-read').on('click', function() {
                    let request = sendMarkRequest($(this).data('id'));
                    request.done(() => {
                        $(this).parents('div.alert').remove();
                    });
                });
                $('.smark-all').on('click', function() {

                    let request = sendMarkRequest();
                    request.done(() => {
                        $('div.alert').remove();
                    })
                });

                $('body').on('click', '.mark-read', function(e) {
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: '<?php echo e(route('admin.notify.markallread')); ?>',
                        success: function(data) {
                            location.reload();
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                });

                // Clear Cache
                $('body').on('click', '.sprukoclearcache', function(e) {
                    e.preventDefault();


                    $('.sprukoclearcache').html(
                        '<svg class="sidemenu_icon" xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M11 11h2V4q0-.425-.287-.713Q12.425 3 12 3t-.712.287Q11 3.575 11 4Zm-6 4h14v-2H5Zm-1.45 6H6v-2q0-.425.287-.712Q6.575 18 7 18t.713.288Q8 18.575 8 19v2h3v-2q0-.425.288-.712Q11.575 18 12 18t.713.288Q13 18.575 13 19v2h3v-2q0-.425.288-.712Q16.575 18 17 18t.712.288Q18 18.575 18 19v2h2.45l-1-4H4.55l-1 4Zm16.9 2H3.55q-.975 0-1.575-.775t-.35-1.725L3 15v-2q0-.825.587-1.413Q4.175 11 5 11h4V4q0-1.25.875-2.125T12 1q1.25 0 2.125.875T15 4v7h4q.825 0 1.413.587Q21 12.175 21 13v2l1.375 5.5q.325.95-.287 1.725-.613.775-1.638.775ZM19 13H5h14Zm-6-2h-2 2Z"/></svg> Clearing Cache ... <i class="fa fa-spinner fa-spin"></i>'
                    );
                    $.ajax({
                        type: "POST",
                        url: '<?php echo e(route('admin.clearcache')); ?>',
                        success: function(data) {

                            toastr.success(data.success);
                            location.reload();
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                });


            })(jQuery);
        <?php endif; ?>

        // Csrf Field
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let playAudio = () => {
            let audio = new Audio();
            audio.src = "<?php echo e(asset('build/assets/sounds/norifysound.mp3')); ?>";
            audio.load();
            audio.play();
        }


        <?php if(setting('admin_users_inactive_auto_logout') == 'on'): ?>
            let lastActve = <?php echo json_encode(session('last_activity')); ?>;
            let showAlertOnceAfter = <?php echo json_encode(session('showAlertOnceAfter')); ?>;
            let logoutOnceafter = <?php echo json_encode(session('logoutOnceafter')); ?>;
            let isPopupShown = false;

            if (localStorage.getItem('showAlertInactive')) {
                $('#adminautologout').modal('show');
            }

            localStorage.setItem('showAlertOnceAfter', showAlertOnceAfter);
            localStorage.setItem('logoutOnceafter', logoutOnceafter);

            setInterval(() => {
                if (new Date(localStorage.getItem('showAlertOnceAfter') ?? showAlertOnceAfter) <=
                    new Date() && !isPopupShown) {
                    localStorage.setItem('showAlertInactive', true);
                    $('#adminautologout').modal('show');
                    isPopupShown = true;
                }
                if (isPopupShown && localStorage.getItem('showAlertInactive')) {
                    if ($('#adminautologout').css('display') === 'none') {
                        $('#adminautologout').modal('show');
                    }
                    if(calculateDateDifferenceInSeconds(localStorage.getItem('logoutOnceafter')) > 0){
                        $('.countdown').html(`${calculateDateDifferenceInSeconds(localStorage.getItem('logoutOnceafter'))}`);
                    }
                }
                if (!localStorage.getItem('showAlertInactive')) {
                    $('#adminautologout').modal('hide');
                }
                if (new Date(localStorage.getItem('logoutOnceafter') ?? logoutOnceafter) <= new Date()) {
                    localStorage.setItem('adminPanelSessionTimeout', true);
                    localStorage.removeItem('showAlertInactive');
                    isPopupShown = false;
                    LogouAdminUSer();
                }
            }, 1000);
            function calculateDateDifferenceInSeconds(dateString) {
                var inputDate = new Date(dateString);
                var currentDate = new Date();
                var differenceInMilliseconds = inputDate - currentDate;
                var differenceInSeconds = Math.floor(differenceInMilliseconds / 1000);
                return differenceInSeconds;
            }
            function LogouAdminUSer() {
                $.ajax({
                    url: "<?php echo e(route('admin.sessionLogout')); ?>",
                    type: 'Post',
                    dataType: 'json',
                    success: function(res) {
                        location.reload();
                    },
                    error: function(res) {
                        location.reload();
                    }
                });
            }

            $('.adminstayin').on('click', function() {
                $.ajax({
                    url: "<?php echo e(route('admin.sessionLogout')); ?>",
                    type: 'Post',
                    dataType: 'json',
                    data: {
                        stayin: true,
                    },
                    success: function(res) {
                        if (res == 1) {
                            $('.countdown').hide();
                            $('#adminautologout').modal('hide');
                            localStorage.removeItem('showAlertInactive');
                            location.reload();
                        }
                    }
                });
            });
            window.addEventListener('beforeunload', () => {
                localStorage.removeItem('adminPanelSessionTimeout');
                localStorage.removeItem('showAlertInactive');
            })
        <?php endif; ?>

        setInterval(function() {
            $.ajax({
                url: "<?php echo e(route('update.notificationalerts')); ?>",
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    $.map(res, function(value, index) {
                        if (index === 0) {
                            toastr.success('<?php echo e(lang('You have')); ?> ' + res.length +
                                ' <?php echo e(lang('new notification')); ?>');
                            readnotify();
                            playAudio();
                        }

                    });
                }
            });

            notificationsreading();
            badgecount();
            markasreadcount();
        }, 5000);

        function readnotify() {

            $.ajax({
                url: "<?php echo e(route('update.notificationalertsread')); ?>",
                type: 'post',
                dataType: 'json',
                success: function(res) {

                }
            });
        }

        $('#notifyreading').load('<?php echo e(route('notificationsreading')); ?>')

        function notificationsreading() {

            $('#notifyreading').load('<?php echo e(route('notificationsreading')); ?>')

        }

        function badgecount() {

            $('#badgecount').load('<?php echo e(route('badgecount')); ?>')

        }

        function markasreadcount() {

            $('#markasreadcount').load('<?php echo e(route('markasreadcount')); ?>')

        }

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker
                .register("<?php echo e(url('/')); ?>/sw.js")
        }

        var inspectDisable = "<?php echo e(setting('inspectDisable')); ?>"
        var selectDisabled = "<?php echo e(setting('selectDisabled')); ?>"

        if (inspectDisable == 'on') {
            document.addEventListener('contextmenu', function(event) {
                event.preventDefault();
            });
            document.onkeydown = function(e) {
                if (e.code == 'F12') {
                    return false;
                }
                if ((e.ctrlKey || e.metaKey) && e.shiftKey && (e.key === 'I' || e.key === 'C')) {
                    e.preventDefault();
                    return false;
                }
            }
        }

        if (selectDisabled == 'on') {
            document.addEventListener('selectstart', function(event) {
                const editableElements = ['INPUT', 'TEXTAREA', 'DIV'];
                const isEditable = editableElements.some(tag => event.target.tagName === tag && event.target.isContentEditable);

                if (!isEditable) {
                    event.preventDefault();
                }
            });
        }

    })
</script>
<?php /**PATH /var/www/html/resources/views/includes/admin/scripts.blade.php ENDPATH**/ ?>