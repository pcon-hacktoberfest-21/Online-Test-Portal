<script>
    function registerForTest(test, isPassword) {
        if (isPassword) {
            enrollEncryptedTest(test);
        } else {
            enrollNOW(test);
        }
    }

    function enrollNOW(test) {
        $.confirm({
            type: 'blue',
            typeAnimated: true,
            title: 'Please Confirm!',
            content: 'Do You really Want to enroll in this test?',
            type: 'purple',
            buttons: {
                No: {
                    text: 'No',
                    btnClass: 'btn-dark'
                },
                Yes: {
                    text: 'Yes',
                    btnClass: 'btn-green',
                    action: function() {
                        var animating = $.confirm({
                            title: '',
                            content: '',
                            content: function() {
                                var self = this;
                                var enrollObject = new FormData();
                                enrollObject.append("test_id", test);
                                var settings = {
                                    "url": "<?= getenv('app.baseURL') ?>/Dashboard/Enroll",
                                    "method": "POST",
                                    "processData": false,
                                    "contentType": false,
                                    "data": enrollObject
                                };
                                return $.ajax(settings).done(function(response) {
                                    console.log(response);
                                    if (response == 1) {
                                        window.history.go(0);
                                    } else {
                                        animating.setTitle(response);
                                    }
                                }).fail(function() {
                                    self.setContent('Something went wrong.');
                                });
                            }
                        });
                    }
                }
            }
        });
    }


    function enrollEncryptedTest(test) {
        $.confirm({
            type: 'blue',
            typeAnimated: true,
            title: '',
            content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>Please Enter test password:</label>' +
                '<input type="password" placeholder="Test Password" class="password form-control" required />' +
                '</div>' +
                '</form>',
            buttons: {
                cancel: function() {
                    //close
                },
                formSubmit: {
                    text: 'Register Now',
                    btnClass: 'btn-blue',
                    action: function() {
                        var password = this.$content.find('.password').val();
                        if (!password) {
                            return false;
                        }

                        var animating = $.confirm({
                            title: '',
                            content: '',
                            content: function() {
                                var self = this;
                                var enrollObject = new FormData();

                                enrollObject.append("test_id", test);
                                enrollObject.append("password", password);

                                var settings = {
                                    "url": "<?= getenv('app.baseURL') ?>/Dashboard/Enroll",
                                    "method": "POST",
                                    "processData": false,
                                    "contentType": false,
                                    "data": enrollObject
                                };
                                return $.ajax(settings).done(function(response) {
                                    console.log(response);
                                    if (response == 1) {
                                        window.history.go(0);
                                    } else {
                                        // $.alert(response);
                                        animating.setTitle(response);
                                    }
                                }).fail(function() {
                                    self.setContent('Something went wrong.');
                                });
                            },
                            buttons: {
                                okay: function() {}
                            }
                        });
                    }
                },
            },
            onContentReady: function() {
                var jc = this;
                this.$content.find('form').on('submit', function(e) {
                    e.preventDefault();
                    jc.$$formSubmit.trigger('click');
                });
            }
        });

    }
</script>

<script>
    function goToTestPage(id) {
        $.confirm({
            title: 'Please Confirm',
            content: 'Do You really want to start test?',
            buttons: {
                "Yes": function() {
                    if ((typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1)) {
                        $.confirm({
                            title: 'Important Message',
                            content: 'It is highly recommended that please increase your screen timing. Dim Screen during test will be recorded as tab change that lead to affect your score',
                            buttons: {
                                "I already increased": function() {
                                    window.location = `<?= getenv('app.baseURL') ?>/Test/MSolve/` + id
                                },
                                "Let me increase": function() {},
                            }
                        });
                    } else {
                        window.location = `<?= getenv('app.baseURL') ?>/Test/Solve/` + id
                    }
                },
                "No": function() {

                },
            }
        });
    }
</script>

<script>
    function searchTest() {
        var test_id = $("#test_id_field").val()
        if (test_id) {
            window.location = "<?= getenv('app.baseURL') ?>/Exam/" + test_id
        }
        return false;
    }
</script>