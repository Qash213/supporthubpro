  <!-- Back to top -->
  <a href="#top" id="back-to-top"><span class="feather feather-chevrons-up"></span></a>


  <!--Moment js-->
  <script src="{{ asset('build/assets/plugins/moment/moment.js') }}?v=<?php echo time(); ?>"></script>

  <!-- Bootstrap4 js-->
  <script src="{{ asset('build/assets/plugins/bootstrap/popper.min.js') }}?v=<?php echo time(); ?>"></script>
  <script src="{{ asset('build/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}?v=<?php echo time(); ?>"></script>


  <!-- P-scroll js-->
  <script src="{{ asset('build/assets/plugins/p-scrollbar/p-scrollbar.js') }}?v=<?php echo time(); ?>"></script>

  <!-- Select2 js -->
  <script src="{{ asset('build/assets/plugins/select2/select2.full.min.js') }}?v=<?php echo time(); ?>"></script>

  <!--INTERNAL Horizontalmenu js -->
  <script src="{{ asset('build/assets/plugins/horizontal-menu/horizontal-menu.js') }}?v=<?php echo time(); ?>"></script>

  <!--INTERNAL Sticky js -->
  <script src="{{ asset('build/assets/plugins/sticky/sticky2.js') }}?v=<?php echo time(); ?>"></script>

  @yield('scripts')

  <!--INTERNAL Toastr js -->
  <script src="{{ asset('build/assets/plugins/toastr/toastr.min.js') }}?v=<?php echo time(); ?>"></script>

  <!--INTERNAL sweetalert js -->
  <script src="{{ asset('build/assets/plugins/sweet-alert/sweetalert.min.js') }}?v=<?php echo time(); ?>"></script>

  <script>
    var inspectDisable = "{{ setting('inspectDisable') }}"
                var selectDisabled = "{{ setting('selectDisabled') }}"

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
  </script>


  <script type="text/javascript">
      $(function() {
          "use strict";

          // Custom js
          @php echo customcssjs('CUSTOMJS') @endphp

          @guest
      @else
          @if (Auth::guard('customer')->check() && Auth::guard('customer')->user()->image != null)

              // Remove Image
              var SITEURL = '{{ url('') }}';

              function deletePost(event) {
                  var id = $(event).data("id");
                  let _url = `{{ url('/customer/image/remove/${id}') }}`;

                  let _token = $('meta[name="csrf-token"]').attr('content');

                  swal({
                          title: `{{ lang('Are you sure you want to continue?', 'alerts') }}`,
                          text: "{{ lang('This might erase your records permanently', 'alerts') }}",
                          icon: "warning",
                          buttons: true,
                          dangerMode: true,
                      })
                      .then((willDelete) => {
                          if (willDelete) {
                              $.ajax({
                                  url: _url,
                                  type: 'DELETE',
                                  data: {
                                      _token: _token
                                  },
                                  success: function(response) {
                                      toastr.success(response.success);
                                      location.reload();
                                  },
                                  error: function(data) {
                                      console.log('Error:', data);
                                  }
                              });
                          }
                      });
              }
          @endif
          @if (auth()->guard('customer')->user())

            let playAudio = () => {
                let audio = new Audio();
                audio.src = "{{ asset('build/assets/sounds/norifysound.mp3') }}";
                audio.load();
                audio.play();
            }

            @if(setting('customer_inactive_auto_logout') == 'on')
                let lastActve = {!! json_encode(session('cust_last_activity')) !!};
                let custshowAlertOnceAfter = {!! json_encode(session('custshowAlertOnceAfter')) !!};
                let custlogoutOnceafter = {!! json_encode(session('custlogoutOnceafter')) !!};
                let iscustPopupShown = false;

                if (localStorage.getItem('custshowAlertInactive')) {
                    $('#customerautologout').modal('show');
                }

                localStorage.setItem('custshowAlertOnceAfter', custshowAlertOnceAfter);
                localStorage.setItem('custlogoutOnceafter', custlogoutOnceafter);

                setInterval(() => {
                    if (new Date(localStorage.getItem('custshowAlertOnceAfter') ?? custshowAlertOnceAfter) <=
                        new Date() && !iscustPopupShown) {
                        localStorage.setItem('custshowAlertInactive', true);
                        $('#customerautologout').modal('show');
                        iscustPopupShown = true;
                    }
                    if (iscustPopupShown && localStorage.getItem('custshowAlertInactive')) {
                        if ($('#customerautologout').css('display') === 'none') {
                            $('#customerautologout').modal('show');
                        }
                        if(custcalculateDateDifferenceInSeconds(localStorage.getItem('custlogoutOnceafter')) > 0){
                            $('.countdown').html(`${custcalculateDateDifferenceInSeconds(localStorage.getItem('custlogoutOnceafter'))}`);
                        }
                    }
                    if (!localStorage.getItem('custshowAlertInactive')) {
                        $('#customerautologout').modal('hide');
                    }
                    if (new Date(localStorage.getItem('custlogoutOnceafter') ?? custlogoutOnceafter) <= new Date()) {
                        localStorage.setItem('custPanelSessionTimeout', true);
                        localStorage.removeItem('custshowAlertInactive');
                        iscustPopupShown = false;
                        LogoutCustUSer();
                    }
                }, 1000);
                function custcalculateDateDifferenceInSeconds(dateString) {
                    var inputDate = new Date(dateString);
                    var currentDate = new Date();
                    var differenceInMilliseconds = inputDate - currentDate;
                    var differenceInSeconds = Math.floor(differenceInMilliseconds / 1000);
                    return differenceInSeconds;
                }
                function LogoutCustUSer() {
                    $.ajax({
                        url: "{{ route('client.sessionLogout') }}",
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

                $('.clientstayin').on('click', function() {
                    $.ajax({
                        url: "{{ route('client.sessionLogout') }}",
                        type: 'Post',
                        dataType: 'json',
                        data: {
                            stayin: true,
                        },
                        success: function(res) {
                            if (res == 1) {
                                $('.countdown').hide();
                                $('#customerautologout').modal('hide');
                                localStorage.removeItem('custshowAlertInactive');
                                location.reload();
                            }
                        }
                    });
                });
                window.addEventListener('beforeunload', () => {
                    localStorage.removeItem('custPanelSessionTimeout');
                    localStorage.removeItem('custshowAlertInactive');
                })
            @endif

              setInterval(function() {

                  $.ajax({
                      url: "{{ route('customer.update.notificationalerts') }}",
                      type: 'GET',
                      dataType: 'json',
                      success: function(res) {
                          $.map(res, function(value, index) {
                              if (index === 0) {
                                  toastr.success('{{ lang('You have') }} ' + res
                                      .length + ' {{ lang('new notification') }}'
                                      );
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
                      url: "{{ route('customer.update.notificationalertsread') }}",
                      type: 'post',
                      dataType: 'json',
                      success: function(res) {

                      }
                  });
              }

              $('.notifyreading').load('{{ route('customer.notificationsreading') }}')

              function notificationsreading() {

                  $('.notifyreading').load('{{ route('customer.notificationsreading') }}')

              }

              function badgecount() {

                  $('.badgecount').load('{{ route('customer.badgecount') }}')

              }

              function markasreadcount() {


                  $('.markasreadcount').load('{{ route('customer.markasreadcount') }}')

              }

              // Mark as Read
              function sendMarkRequest(id = null) {
                  return $.ajax("{{ route('customer.markNotification') }}", {
                      method: 'GET',
                      data: {
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
                  $('.cmark-all').on('click', function() {
                      let request = sendMarkRequest();
                      request.done(() => {
                          $('div.alert').remove();
                      })
                  });

                  $('body').on('click', '.mark-read', function(e) {
                      e.preventDefault();
                      $.ajax({
                          type: "POST",
                          url: '{{ route('customer.notify.markallread') }}',
                          success: function(data) {
                              notificationsreading();
                              badgecount();
                              // location.reload();
                          },
                          error: function(data) {
                              console.log('Error:', data);
                          }
                      });
                  });


              })(jQuery);
          @endif
      @endguest
      })
  </script>

  <!-- Custom html js-->
  @vite(['resources/assets/js/custom.js'])
