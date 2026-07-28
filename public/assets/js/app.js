/**
 * NEC South Sudan - Main Application JavaScript
 * National Election Commission South Sudan
 * Modern, animated, interactive JS
 */

(function($) {
    'use strict';

    // ========================================================================
    // PRELOADER
    // ========================================================================

    function initPreloader() {
        var preloader = document.getElementById('necPreloader');
        if (!preloader) return;

        function hidePreloader() {
            preloader.classList.add('loaded');
        }

        // Try jQuery first if available
        if (typeof $ !== 'undefined' && $.fn && $.fn.jquery) {
            var $preloader = $(preloader);
            $(window).on('load', function() {
                setTimeout(hidePreloader, 600);
            });
            if (document.readyState === 'complete') {
                setTimeout(hidePreloader, 800);
            }
        } else {
            // Vanilla JS fallback
            window.addEventListener('load', function() {
                setTimeout(hidePreloader, 600);
            });
            if (document.readyState === 'complete') {
                setTimeout(hidePreloader, 800);
            }
        }
    }

    // ========================================================================
    // UTILITY FUNCTIONS
    // ========================================================================

    function formatNumber(n) {
        if (n === null || n === undefined) return '0';
        return Number(n).toLocaleString();
    }

    function formatDate(dateStr) {
        if (!dateStr) return '';
        var d = new Date(dateStr);
        if (isNaN(d.getTime())) return dateStr;
        return d.toLocaleDateString('en-SS', {
            year: 'numeric', month: 'long', day: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });
    }

    function ucfirst(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function debounce(func, wait) {
        var timeout;
        return function executedFunction() {
            var context = this;
            var args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                func.apply(context, args);
            }, wait);
        };
    }

    function truncate(str, len) {
        if (!str) return '';
        if (str.length <= len) return str;
        return str.substring(0, len) + '...';
    }

    // ========================================================================
    // INIT: NAVBAR
    // ========================================================================

    function initNavbar() {
        var $navbar = $('.nec-navbar, .navbar-main');
        var $scrollTop = $('#scrollToTop, .scroll-top');

        if (!$navbar.length) return;

        $(window).on('scroll', function() {
            var scrollTop = $(this).scrollTop();

            if (scrollTop > 100) {
                $navbar.addClass('scrolled nec-navbar-scrolled');
            } else {
                $navbar.removeClass('scrolled nec-navbar-scrolled');
            }

            if ($scrollTop.length) {
                if (scrollTop > 300) {
                    $scrollTop.addClass('show');
                } else {
                    $scrollTop.removeClass('show');
                }
            }
        });

        // Auto-close mobile menu on outside click
        $(document).on('click', function(e) {
            if ($(window).width() < 992) {
                var $toggler = $navbar.find('.navbar-toggler');
                var $collapse = $navbar.find('.navbar-collapse');
                if ($collapse.hasClass('show') && !$(e.target).closest($navbar).length) {
                    $toggler.trigger('click');
                }
            }
        });
    }

    // ========================================================================
    // INIT: SCROLL TO TOP
    // ========================================================================

    function initScrollToTop() {
        var $btn = $('#scrollToTop, .scroll-top');
        if (!$btn.length) return;

        $btn.on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: 0 }, 600, 'swing');
        });
    }

    // ========================================================================
    // INIT: ANIMATED COUNTERS (IntersectionObserver + rAF)
    // ========================================================================

    function initAnimatedCounters() {
        var counters = document.querySelectorAll('[data-count]');
        if (!counters.length) return;

        if (!('IntersectionObserver' in window)) {
            counters.forEach(function(el) {
                var val = parseInt(el.getAttribute('data-count'), 10);
                if (!isNaN(val)) el.textContent = val.toLocaleString();
            });
            return;
        }

        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                var el = entry.target;
                if (!entry.isIntersecting) return;
                if (el.getAttribute('data-animated') === 'true') return;

                el.setAttribute('data-animated', 'true');
                observer.unobserve(el);

                var target = parseInt(el.getAttribute('data-count'), 10);
                if (isNaN(target)) return;

                var start = 0;
                var duration = 2000;
                var startTime = null;

                function step(timestamp) {
                    if (!startTime) startTime = timestamp;
                    var progress = Math.min((timestamp - startTime) / duration, 1);
                    var current = Math.floor(progress * target);
                    el.textContent = current.toLocaleString();
                    if (progress < 1) {
                        requestAnimationFrame(step);
                    } else {
                        el.textContent = target.toLocaleString();
                    }
                }

                requestAnimationFrame(step);
            });
        }, { threshold: 0.3 });

        counters.forEach(function(el) {
            observer.observe(el);
        });
    }

    // ========================================================================
    // INIT: COUNTDOWN
    // ========================================================================

    function initCountdown() {
        var $days = $('#countDays');
        var $hours = $('#countHours');
        var $minutes = $('#countMinutes');
        var $seconds = $('#countSeconds');
        var $container = $days.closest('#election-countdown, .countdown-container');

        if (!$days.length && !$container.length) return;

        var targetDate = new Date('2026-12-22T00:00:00').getTime();

        function pad(n) {
            return n.toString().padStart(2, '0');
        }

        function update() {
            var now = new Date().getTime();
            var distance = targetDate - now;

            if (distance <= 0) {
                if ($container.length) {
                    $container.html(
                        '<div class="countdown-expired text-center py-4">' +
                        '<h3 class="text-nec-green fw-bold mb-0"><i class="fas fa-check-circle me-2"></i>Election Day!</h3>' +
                        '<p class="text-muted mt-2 mb-0">Voting is underway across South Sudan</p>' +
                        '</div>'
                    );
                }
                return;
            }

            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hrs = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var mins = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var secs = Math.floor((distance % (1000 * 60)) / 1000);

            if ($days.length) { $days.text(pad(days)).addClass('flip-animate'); }
            if ($hours.length) { $hours.text(pad(hrs)).addClass('flip-animate'); }
            if ($minutes.length) { $minutes.text(pad(mins)).addClass('flip-animate'); }
            if ($seconds.length) { $seconds.text(pad(secs)).addClass('flip-animate'); }
        }

        // Remove animation class after transition
        $(document).on('animationend', '.flip-animate', function() {
            $(this).removeClass('flip-animate');
        });

        update();
        setInterval(update, 1000);
    }

    // ========================================================================
    // INIT: CHARTS (Chart.js)
    // ========================================================================

    function initCharts() {
        if (typeof Chart === 'undefined') return;

        Chart.defaults.color = '#495057';
        Chart.defaults.borderColor = '#e9ecef';

        // -- Registration Trend --
        (function() {
            var canvas = document.getElementById('registrationChart') ||
                         document.getElementById('registrationTrendsChart');
            if (!canvas) return;
            var $c = $(canvas);
            var labels, values;
            try {
                labels = JSON.parse($c.attr('data-labels') || '["Jan","Feb","Mar","Apr","May","Jun"]');
                values = JSON.parse($c.attr('data-values') || null);
            } catch(e) {
                labels = ['Jan','Feb','Mar','Apr','May','Jun'];
                values = null;
            }
            if (!values) {
                values = [10000, 25000, 45000, 70000, 95000, 120000];
            }
            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Registered Voters',
                        data: values,
                        borderColor: '#2E8B57',
                        backgroundColor: function(ctx) {
                            var g = ctx.chart.ctx.createLinearGradient(0, 0, 0, ctx.chart.height);
                            g.addColorStop(0, 'rgba(46,139,87,0.35)');
                            g.addColorStop(1, 'rgba(46,139,87,0.02)');
                            return g;
                        },
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#2E8B57',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#212529',
                            bodyColor: '#495057',
                            borderColor: '#2E8B57',
                            borderWidth: 2,
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(val) { return val.toLocaleString(); }
                            },
                            grid: { color: 'rgba(0,0,0,0.06)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    animation: {
                        duration: 1200,
                        easing: 'easeOutQuart'
                    }
                }
            });
        })();

        // -- Gender Distribution --
        (function() {
            var canvas = document.getElementById('genderChart') ||
                         document.getElementById('genderDistChart');
            if (!canvas) return;
            var $c = $(canvas);
            var values;
            try {
                values = JSON.parse($c.attr('data-values') || null);
            } catch(e) { values = null; }
            if (!values) values = [52, 48];

            new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels: ['Male', 'Female'],
                    datasets: [{
                        data: values,
                        backgroundColor: ['#2E8B57', '#D4AF37'],
                        borderWidth: 0,
                        hoverOffset: 12
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: { size: 13 }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#212529',
                            bodyColor: '#495057',
                            borderColor: '#2E8B57',
                            borderWidth: 2,
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(ctx) {
                                    var total = ctx.dataset.data.reduce(function(a, b) { return a + b; }, 0);
                                    var pct = ((ctx.parsed / total) * 100).toFixed(1);
                                    return ctx.label + ': ' + ctx.parsed.toLocaleString() + ' (' + pct + '%)';
                                }
                            }
                        }
                    },
                    animation: {
                        animateRotate: true,
                        duration: 1000
                    }
                }
            });
        })();

        // -- Election Results --
        (function() {
            var canvas = document.getElementById('resultsChart');
            if (!canvas) return;
            var $c = $(canvas);
            var parties, votes, colors;
            try {
                parties = JSON.parse($c.attr('data-parties') || null);
                votes = JSON.parse($c.attr('data-votes') || null);
                colors = JSON.parse($c.attr('data-colors') || null);
            } catch(e) { parties = null; votes = null; colors = null; }
            if (!parties) parties = ['SSPDF', 'SPLM-IO', 'Other'];
            if (!votes) votes = [450000, 320000, 180000];
            if (!colors) colors = ['#2E8B57', '#D4AF37', '#1a3c8f'];

            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: parties,
                    datasets: [{
                        label: 'Votes',
                        data: votes,
                        backgroundColor: colors,
                        borderWidth: 0,
                        borderRadius: 6,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#212529',
                            bodyColor: '#495057',
                            borderColor: '#2E8B57',
                            borderWidth: 2,
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(ctx) {
                                    return ctx.parsed.x.toLocaleString() + ' votes';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(val) { return val.toLocaleString(); }
                            },
                            grid: { color: 'rgba(0,0,0,0.06)' }
                        },
                        y: {
                            grid: { display: false }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutBounce'
                    }
                }
            });
        })();

        // -- Voter Turnout --
        (function() {
            var canvas = document.getElementById('turnoutChart');
            if (!canvas) return;
            var $c = $(canvas);
            var labels, values;
            try {
                labels = JSON.parse($c.attr('data-labels') || null);
                values = JSON.parse($c.attr('data-values') || null);
            } catch(e) { labels = null; values = null; }
            if (!labels) labels = ['Jubek', 'Terekeka', 'Yei River', 'Wau', 'Bor', 'Malakal'];
            if (!values) values = [72, 65, 68, 58, 71, 63];

            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Turnout %',
                        data: values,
                        backgroundColor: 'rgba(46,139,87,0.7)',
                        borderColor: '#2E8B57',
                        borderWidth: 2,
                        borderRadius: 4,
                        barPercentage: 0.5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#212529',
                            bodyColor: '#495057',
                            borderColor: '#2E8B57',
                            borderWidth: 2,
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(ctx) {
                                    return ctx.parsed.y + '% turnout';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(val) { return val + '%'; }
                            },
                            grid: { color: 'rgba(0,0,0,0.06)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });
        })();

        // -- Data-attribute driven charts --
        $('canvas[data-chart-type]').each(function() {
            var $c = $(this);
            var type = $c.data('chart-type');
            if (['doughnut', 'bar', 'line'].indexOf(type) === -1) return;
            if ($c.attr('id')) return; // already handled above

            var labels, values;
            try {
                labels = JSON.parse($c.attr('data-labels') || '[]');
                values = JSON.parse($c.attr('data-values') || '[]');
            } catch(e) { return; }
            if (!labels.length || !values.length) return;

            var bgColor = type === 'doughnut'
                ? ['#2E8B57', '#D4AF37', '#1a3c8f', '#DA291C', '#17a2b8', '#6f42c1']
                : '#2E8B57';

            new Chart(this, {
                type: type,
                data: {
                    labels: labels,
                    datasets: [{
                        label: $c.data('label') || 'Data',
                        data: values,
                        backgroundColor: bgColor,
                        borderColor: type === 'line' ? '#2E8B57' : undefined,
                        borderWidth: type === 'line' ? 3 : 0,
                        fill: type === 'line',
                        tension: type === 'line' ? 0.4 : undefined
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: type === 'doughnut' },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#212529',
                            bodyColor: '#495057',
                            borderColor: '#2E8B57',
                            borderWidth: 2,
                            padding: 12,
                            cornerRadius: 8
                        }
                    },
                    scales: type !== 'doughnut' ? {
                        y: { beginAtZero: true },
                        x: { grid: { display: false } }
                    } : undefined,
                    cutout: type === 'doughnut' ? '70%' : undefined
                }
            });
        });
    }

    // ========================================================================
    // INIT: DATA TABLES
    // ========================================================================

    function initDataTables() {
        if (typeof $.fn.DataTable === 'undefined') return;

        $('.data-table').each(function() {
            var $table = $(this);
            if ($table.data('initialized')) return;
            $table.data('initialized', true);

            $table.DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                language: {
                    search: '<i class="fas fa-search me-1"></i>Search:',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                    infoEmpty: 'No entries available',
                    infoFiltered: '(filtered from _MAX_ total entries)',
                    zeroRecords: 'No matching records found',
                    emptyTable: 'No data available',
                    paginate: {
                        first: '<i class="fas fa-angle-double-left"></i>',
                        last: '<i class="fas fa-angle-double-right"></i>',
                        next: '<i class="fas fa-angle-right"></i>',
                        previous: '<i class="fas fa-angle-left"></i>'
                    }
                },
                dom: '<"row mb-3"<"col-sm-6"l><"col-sm-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row mt-3"<"col-sm-5"i><"col-sm-7"p>>'
            });
        });
    }

    // ========================================================================
    // INIT: FORM VALIDATION
    // ========================================================================

    function initFormValidation() {
        $('.needs-validation').each(function() {
            var form = this;
            $(form).on('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                $(form).addClass('was-validated');
            });
        });
    }

    // ========================================================================
    // INIT: AUTO-REFRESH (live results)
    // ========================================================================

    function initAutoRefresh() {
        var $el = $('#live-results');
        if (!$el.length) return;

        var url = $el.data('url') || '/api/v1/results/live';
        var interval = $el.data('refresh') || 30000;

        function fetchResults() {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $el.addClass('loading');
                },
                success: function(data) {
                    $el.removeClass('loading error');
                    var items = data.results || data.data || data;
                    if (!$.isArray(items)) items = [];
                    if (items.length) {
                        var html = '';
                        items.forEach(function(r) {
                            var name = r.party_name || r.party || r.name || 'N/A';
                            var votes = r.votes || r.count || 0;
                            var pct = r.percentage || r.pct || 0;
                            html += '<div class="result-item d-flex justify-content-between align-items-center py-2 border-bottom">' +
                                    '<span class="party-name fw-medium">' + $('<span>').text(name).html() + '</span>' +
                                    '<span class="vote-count fw-bold text-nec-green">' + Number(votes).toLocaleString() + '</span>' +
                                    '</div>';
                        });
                        $el.html(html);
                    } else {
                        $el.html('<p class="text-muted text-center py-3 mb-0">No live results available yet.</p>');
                    }
                },
                error: function(jqXHR) {
                    $el.removeClass('loading').addClass('error');
                    var msg = 'Failed to load live results.';
                    if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                        msg = jqXHR.responseJSON.message;
                    }
                    $el.html('<p class="text-danger text-center py-3 mb-0"><i class="fas fa-exclamation-triangle me-2"></i>' + msg + '</p>');
                }
            });
        }

        fetchResults();
        setInterval(fetchResults, interval);
    }

    // ========================================================================
    // INIT: SEARCH MODAL
    // ========================================================================

    function initSearchModal() {
        var $input = $('#searchInput');
        var $results = $('#searchResults');
        if (!$input.length) return;

        var doSearch = debounce(function() {
            var query = $input.val().trim();
            if (query.length < 3) {
                $results.html('<p class="text-muted text-center p-4 mb-0">Type at least 3 characters to search...</p>');
                return;
            }

            $.ajax({
                url: '/api/v1/search?q=' + encodeURIComponent(query),
                method: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $results.html(
                        '<div class="text-center p-4">' +
                        '<div class="spinner-border text-nec-green" role="status">' +
                        '<span class="visually-hidden">Loading...</span></div>' +
                        '<p class="text-muted mt-2 mb-0">Searching...</p></div>'
                    );
                },
                success: function(data) {
                    var items = data.results || data.data || [];
                    if (!$.isArray(items)) items = [];
                    if (items.length) {
                        var html = '<div class="list-group list-group-flush">';
                        items.forEach(function(item) {
                            var title = item.title || item.name || 'Untitled';
                            var desc = item.description || item.excerpt || '';
                            var url = item.url || item.link || '#';
                            html += '<a href="' + url + '" class="list-group-item list-group-item-action px-4 py-3">' +
                                    '<div class="fw-semibold">' + $('<span>').text(title).html() + '</div>' +
                                    (desc ? '<small class="text-muted">' + $('<span>').text(truncate(desc, 120)).html() + '</small>' : '') +
                                    '</a>';
                        });
                        html += '</div>';
                        $results.html(html);
                    } else {
                        $results.html(
                            '<div class="text-center p-4">' +
                            '<i class="fas fa-search fa-2x text-muted mb-2"></i>' +
                            '<p class="text-muted mb-0">No results found for "' + $('<span>').text(query).html() + '"</p></div>'
                        );
                    }
                },
                error: function() {
                    $results.html(
                        '<div class="text-center p-4">' +
                        '<i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>' +
                        '<p class="text-danger mb-0">Search failed. Please try again.</p></div>'
                    );
                }
            });
        }, 300);

        $input.on('keyup', doSearch);
    }

    // ========================================================================
    // INIT: NEWSLETTER
    // ========================================================================
    // INIT: TOOLTIPS
    // ========================================================================

    function initTooltips() {
        if (typeof bootstrap === 'undefined' || !bootstrap.Tooltip) return;

        var triggers = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        triggers.forEach(function(el) {
            try {
                new bootstrap.Tooltip(el);
            } catch(e) { /* silently ignore */ }
        });
    }

    // ========================================================================
    // INIT: CONFIRM DIALOGS (SweetAlert2)
    // ========================================================================

    function initConfirmDialogs() {
        if (typeof Swal === 'undefined') return;

        $(document).on('click', '[data-confirm]', function(e) {
            e.preventDefault();
            var $el = $(this);
            var message = $el.data('confirm') || 'Are you sure you want to proceed?';
            var $form = $el.closest('form');

            Swal.fire({
                title: 'Confirm Action',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2E8B57',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-check me-1"></i>Yes, proceed',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    if ($form.length) {
                        $form.trigger('submit');
                    } else if ($el.is('a')) {
                        window.location.href = $el.attr('href');
                    } else if ($el.attr('href')) {
                        window.location.href = $el.attr('href');
                    }
                }
            });
        });

        $(document).on('click', '[data-delete-confirm]', function(e) {
            e.preventDefault();
            var $el = $(this);
            var message = $el.data('delete-confirm') || 'This item will be permanently deleted. Are you sure?';
            var $form = $el.closest('form');

            Swal.fire({
                title: 'Delete Confirmation',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DA291C',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash-alt me-1"></i>Yes, delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    if ($form.length) {
                        $form.trigger('submit');
                    } else {
                        // Fire a delete request via AJAX if data-url is present
                        var url = $el.data('url') || $el.attr('href');
                        if (url && url !== '#') {
                            $.ajax({
                                url: url,
                                method: 'DELETE',
                                dataType: 'json',
                                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                success: function(resp) {
                                    if (resp.success || resp.message) {
                                        Swal.fire({ icon: 'success', title: 'Deleted!', text: resp.message || 'Item deleted.', timer: 2000, timerProgressBar: true });
                                        if (resp.redirect) window.location.href = resp.redirect;
                                        else if ($el.closest('tr').length) $el.closest('tr').fadeOut(300, function() { $(this).remove(); });
                                    }
                                },
                                error: function() {
                                    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to delete item.' });
                                }
                            });
                        }
                    }
                }
            });
        });
    }

    // ========================================================================
    // INIT: FLASH MESSAGES
    // ========================================================================

    function initFlashMessages() {
        var $flash = $('#flash-message');
        if (!$flash.length) return;

        var type = $flash.data('type') || 'info';
        var message = $flash.data('message') || '';
        if (!message) return;

        var iconMap = {
            success: 'success',
            error: 'error',
            warning: 'warning',
            info: 'info'
        };

        if (typeof Swal !== 'undefined') {
            var config = {
                icon: iconMap[type] || 'info',
                title: ucfirst(type),
                text: message,
                timer: 5000,
                timerProgressBar: true,
                toast: false,
                position: 'center',
                showConfirmButton: true,
                confirmButtonColor: '#2E8B57',
                confirmButtonText: 'OK'
            };

            // Show as toast for non-error types
            if (type !== 'error') {
                config.toast = true;
                config.position = 'top-end';
                config.showConfirmButton = false;
            }

            Swal.fire(config);
        }
    }

    // ========================================================================
    // INIT: FILE UPLOAD
    // ========================================================================

    function initFileUpload() {
        // Show filename in custom file input
        $(document).on('change', '.custom-file-input', function() {
            var $input = $(this);
            var files = $input.prop('files');
            var label = files && files.length
                ? (files.length > 1 ? files.length + ' files selected' : files[0].name)
                : 'Choose file...';
            $input.siblings('.custom-file-label, .file-label').addClass('selected').text(label);
        });

        // Image preview
        $(document).on('change', 'input[type="file"][data-preview]', function() {
            var $input = $(this);
            var $preview = $($input.data('preview'));
            var file = $input.prop('files')[0];
            if (!file || !$preview.length) return;

            if (file.type && file.type.indexOf('image') === -1) return;

            var reader = new FileReader();
            reader.onload = function(e) {
                $preview.attr('src', e.target.result).removeClass('d-none').addClass('img-preview-shown');
            };
            reader.readAsDataURL(file);
        });
    }

    // ========================================================================
    // INIT: PASSWORD TOGGLE
    // ========================================================================

    function initPasswordToggle() {
        $(document).on('click', '.toggle-password', function() {
            var $btn = $(this);
            var $input = $($btn.data('target'));
            if (!$input.length) return;

            var $icon = $btn.find('i');
            var isPassword = $input.attr('type') === 'password';

            $input.attr('type', isPassword ? 'text' : 'password');
            $icon.toggleClass('fa-eye fa-eye-slash');
            $btn.attr('aria-label', isPassword ? 'Hide password' : 'Show password');
        });
    }

    // ========================================================================
    // INIT: SMOOTH SCROLL
    // ========================================================================

    function initSmoothScroll() {
        var navbarHeight = 80;

        $(document).on('click', 'a[href^="#"]', function(e) {
            var $link = $(this);
            var href = $link.attr('href');
            if (href === '#' || href === '') return;

            var $target = $(href);
            if (!$target.length) return;

            e.preventDefault();
            var offset = $target.offset().top - navbarHeight;

            $('html, body').animate({ scrollTop: offset }, 600, 'swing', function() {
                // Update URL hash without jumping
                if (history.pushState) {
                    history.pushState(null, null, href);
                }
            });
        });
    }

    // ========================================================================
    // INIT: ANIMATE ON SCROLL (AOS)
    // ========================================================================

    function initAOS() {
        if (typeof AOS !== 'undefined' && typeof AOS.init === 'function') {
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true,
                offset: 60,
                delay: 50,
                disable: 'mobile'
            });
        }
    }

    // ========================================================================
    // INIT: YEAR SELECTOR
    // ========================================================================

    function initYearSelector() {
        var $select = $('#yearSelect');
        if (!$select.length) return;

        $select.on('change', function() {
            var val = $(this).val();
            if (!val) return;

            // Try to submit parent form first
            var $form = $select.closest('form');
            if ($form.length) {
                $form.trigger('submit');
            } else {
                // Otherwise reload with year param
                var url = new URL(window.location.href);
                url.searchParams.set('year', val);
                window.location.href = url.toString();
            }
        });
    }

    // ========================================================================
    // INIT: PULSE UPDATES (live indicators)
    // ========================================================================

    function initPulseUpdates() {
        var $indicators = $('.live-indicator, .pulse-dot');
        if (!$indicators.length) return;

        setInterval(function() {
            $indicators.toggleClass('pulse-active');
        }, 1500);
    }

    // ========================================================================
    // INIT: THEME TOGGLE (light/dark)
    // ========================================================================

    function initThemeToggle() {
        var $btn = $('#themeToggle');
        if (!$btn.length) return;

        var $html = $('html');
        var $icon = $btn.find('i');

        // Load saved preference
        var saved = localStorage.getItem('nec-theme');
        if (saved === 'dark') {
            $html.attr('data-bs-theme', 'dark');
            $icon.removeClass('fa-moon').addClass('fa-sun');
            $btn.attr('aria-label', 'Switch to light mode');
        }

        $btn.on('click', function() {
            var current = $html.attr('data-bs-theme') || 'light';
            var next = current === 'dark' ? 'light' : 'dark';

            $html.attr('data-bs-theme', next);
            localStorage.setItem('nec-theme', next);

            if (next === 'dark') {
                $icon.removeClass('fa-moon').addClass('fa-sun');
                $btn.attr('aria-label', 'Switch to light mode');
            } else {
                $icon.removeClass('fa-sun').addClass('fa-moon');
                $btn.attr('aria-label', 'Switch to dark mode');
            }
        });
    }

    // ========================================================================
    // INIT: SIDEBAR TOGGLE (admin/mobile)
    // ========================================================================

    function initSidebarToggle() {
        var $btn = $('#sidebarToggle');
        if (!$btn.length) return;

        var $sidebar = $('.admin-sidebar');
        var $overlay = $('#sidebarOverlay, .sidebar-overlay');

        // Create overlay if missing but sidebar exists
        if ($sidebar.length && !$overlay.length) {
            $overlay = $('<div id="sidebarOverlay" class="sidebar-overlay"></div>');
            $('body').append($overlay);
        }

        $btn.on('click', function(e) {
            e.stopPropagation();
            $sidebar.toggleClass('show');
            $overlay.toggleClass('show');
            $('body').toggleClass('sidebar-open');
        });

        // Close on overlay click
        $(document).on('click', '#sidebarOverlay, .sidebar-overlay', function() {
            $sidebar.removeClass('show');
            $overlay.removeClass('show');
            $('body').removeClass('sidebar-open');
        });

        // Close on escape
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $sidebar.hasClass('show')) {
                $sidebar.removeClass('show');
                $overlay.removeClass('show');
                $('body').removeClass('sidebar-open');
            }
        });
    }

    // ========================================================================
    // INIT: SCROLL REVEAL (intersection observer)
    // ========================================================================

    function initScrollReveal() {
        var els = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale, .scroll-reveal');
        if (!els.length) return;

        // Add data-reveal attribute to activate JS-driven visibility
        els.forEach(function(el) { el.setAttribute('data-reveal', ''); });

        if (!('IntersectionObserver' in window)) {
            els.forEach(function(el) { el.classList.add('visible'); });
            return;
        }

        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

        els.forEach(function(el) { observer.observe(el); });
    }

    // ========================================================================
    // INIT: TOAST NOTIFICATIONS (polling)
    // ========================================================================

    function initToastNotifications() {
        var $badge = $('.notification-badge');
        if (!$badge.length) return;

        var lastCount = parseInt($badge.text(), 10) || 0;
        var lastChecked = Date.now();

        function checkNotifications() {
            $.ajax({
                url: '/api/v1/notifications/unread-count',
                method: 'GET',
                dataType: 'json',
                cache: false,
                success: function(data) {
                    var count = data.count || data.unread || 0;
                    $badge.text(count > 99 ? '99+' : count);
                    if (count > 99) $badge.addClass('overflow');
                    else $badge.removeClass('overflow');

                    if (count > lastCount && typeof Swal !== 'undefined' && data.notifications) {
                        var newNotifs = data.notifications || [];
                        if (newNotifs.length && Array.isArray(newNotifs)) {
                            newNotifs.forEach(function(n) {
                                var icon = n.type === 'success' ? 'success'
                                    : n.type === 'error' ? 'error'
                                    : n.type === 'warning' ? 'warning' : 'info';
                                Swal.fire({
                                    icon: icon,
                                    title: n.title || 'Notification',
                                    text: n.message || '',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 5000,
                                    timerProgressBar: true
                                });
                            });
                        }
                    }
                    lastCount = count;
                },
                error: function() {
                    // Silently fail — notifications are non-essential
                }
            });
        }

        // Check every 60 seconds
        setInterval(checkNotifications, 60000);
    }

    // ========================================================================
    // INIT: VOTER INQUIRY
    // ========================================================================

    function initVoterInquiry() {
        var $form = $('#voterInquiryForm, .voter-inquiry-form');
        if (!$form.length) return;

        var $results = $('#voterResults, .voter-results');
        var $loading = $('#voterLoading, .voter-loading');
        var $noResults = $('#voterNoResults, .voter-no-results');
        var $error = $('#voterError, .voter-error');

        // Format inputs
        $form.on('input', 'input[type="tel"], input[name="phone"]', function() {
            var val = $(this).val().replace(/[^0-9]/g, '');
            if (val.length > 0 && val.charAt(0) !== '+') {
                val = '+' + val;
            }
            $(this).val(val);
        });

        $form.on('input', 'input[name="voter_id"], input[name="voterId"]', function() {
            $(this).val($(this).val().toUpperCase());
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            var formData = $form.serialize();

            // Hide all result containers
            $results.addClass('d-none');
            $noResults.addClass('d-none');
            $error.addClass('d-none');
            $loading.removeClass('d-none');

            $.ajax({
                url: $form.attr('action') || '/api/v1/voter-inquiry',
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function(data) {
                    $loading.addClass('d-none');
                    if (data.found || (data.voter && data.voter.name)) {
                        if ($results.length) {
                            // Populate fields if using data binding
                            if ($results.find('[data-field]').length) {
                                var voter = data.voter || data;
                                $results.find('[data-field]').each(function() {
                                    var field = $(this).data('field');
                                    $(this).text(voter[field] || '-');
                                });
                            }
                            $results.removeClass('d-none');
                            $('html, body').animate({ scrollTop: $results.offset().top - 100 }, 500);
                        }
                    } else {
                        $noResults.removeClass('d-none');
                    }
                },
                error: function() {
                    $loading.addClass('d-none');
                    $error.removeClass('d-none');
                }
            });
        });
    }

    // ========================================================================
    // DOWNLOAD TRACKING
    // ========================================================================

    function initDownloadTracking() {
        var $links = $('[data-track-download]');
        if (!$links.length) return;

        var slugs = [];
        $links.each(function() {
            slugs.push($(this).data('track-download'));
        });

        $.ajax({
            url: baseUrl + '/api/v1/download',
            method: 'POST',
            data: { slugs: JSON.stringify(slugs), action: 'fetch' },
            dataType: 'json',
            success: function(data) {
                if (!data.counts) return;
                var total = 0;
                $.each(data.counts, function(slug, count) {
                    var $link = $links.filter('[data-track-download="' + slug + '"]');
                    $link.find('.dl-count-badge').text(count);
                    total += count;
                });
                $('.total-dl-count').text(total);
                highlightTopDownloads();
            }
        });

        $links.on('click', function(e) {
            var $link = $(this);
            var slug = $link.data('track-download');
            var label = $link.data('label') || slug;
            var $badge = $link.find('.dl-count-badge');
            var $total = $('.total-dl-count');

            var current = parseInt($badge.text(), 10) || 0;
            $badge.text(current + 1).removeClass('bg-light text-muted').addClass('bg-success text-white');
            var totalCurrent = parseInt($total.text(), 10) || 0;
            $total.text(totalCurrent + 1);

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Download Started',
                    text: label + ' — your download should begin shortly.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            }

            $.ajax({
                url: baseUrl + '/api/v1/download',
                method: 'POST',
                data: { slug: slug, label: label, url: $link.attr('href') },
                dataType: 'json',
                success: function(data) {
                    if (data.count) {
                        $badge.text(data.count);
                    }
                    highlightTopDownloads();
                }
            });
        });
    }

    function highlightTopDownloads() {
        var $badges = $('.dl-count-badge');
        if (!$badges.length) return;

        $badges.removeClass('bg-warning text-dark').addClass('bg-light text-muted');

        var max = 0;
        $badges.each(function() {
            var c = parseInt($(this).text(), 10) || 0;
            if (c > max) max = c;
        });

        if (max > 0) {
            $badges.each(function() {
                var c = parseInt($(this).text(), 10) || 0;
                if (c === max) {
                    $(this).removeClass('bg-light text-muted').addClass('bg-warning text-dark');
                }
            });
        }
    }

    // ========================================================================
    // JQUERY PLUGIN: $.fn.ajaxSubmit
    // ========================================================================

    $.fn.ajaxSubmit = function(options) {
        var settings = $.extend({
            successMessage: 'Operation completed successfully.',
            errorMessage: 'An error occurred. Please try again.',
            redirectUrl: null,
            resetForm: false,
            confirmMessage: null,
            useSwal: true,
            onSuccess: null,
            onError: null
        }, options);

        this.each(function() {
            var $form = $(this);

            $form.on('submit', function(e) {
                e.preventDefault();

                // Optional pre-confirm
                if (settings.confirmMessage && typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Confirm',
                        text: settings.confirmMessage,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#2E8B57',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, submit',
                        cancelButtonText: 'Cancel'
                    }).then(function(confirmed) {
                        if (confirmed.isConfirmed) {
                            doSubmit();
                        }
                    });
                } else {
                    doSubmit();
                }

                function doSubmit() {
                    var formData = new FormData($form[0]);
                    var csrfToken = $('meta[name="csrf-token"]').attr('content') ||
                                    $form.find('input[name="_token"]').val();

                    if (csrfToken && !formData.has('_token') && !formData.has('_csrf')) {
                        formData.append('_token', csrfToken);
                    }

                    if (settings.useSwal && typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Processing...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            didOpen: function() {
                                Swal.showLoading();
                            }
                        });
                    }

                    $.ajax({
                        url: $form.attr('action'),
                        method: $form.attr('method') || 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function(response) {
                            if (settings.useSwal && typeof Swal !== 'undefined') {
                                Swal.close();
                            }

                            if (response.success || response.status === 'success') {
                                if (settings.useSwal && typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: response.message || settings.successMessage,
                                        timer: 2000,
                                        timerProgressBar: true
                                    }).then(function() {
                                        if (settings.redirectUrl) {
                                            window.location.href = settings.redirectUrl;
                                        } else if (response.redirect) {
                                            window.location.href = response.redirect;
                                        }
                                        if (settings.resetForm) {
                                            $form[0].reset();
                                            $form.find('.was-validated').removeClass('was-validated');
                                        }
                                    });
                                } else {
                                    if (settings.redirectUrl) {
                                        window.location.href = settings.redirectUrl;
                                    } else if (response.redirect) {
                                        window.location.href = response.redirect;
                                    }
                                    if (settings.resetForm) {
                                        $form[0].reset();
                                    }
                                }

                                if (typeof settings.onSuccess === 'function') {
                                    settings.onSuccess(response, $form);
                                }
                            } else {
                                if (settings.useSwal && typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message || settings.errorMessage,
                                        confirmButtonColor: '#2E8B57'
                                    });
                                }
                                if (typeof settings.onError === 'function') {
                                    settings.onError(response, $form);
                                }
                            }
                        },
                        error: function(jqXHR) {
                            if (settings.useSwal && typeof Swal !== 'undefined') {
                                Swal.close();
                            }
                            var msg = settings.errorMessage;
                            if (jqXHR.responseJSON) {
                                msg = jqXHR.responseJSON.message || msg;
                                if (jqXHR.responseJSON.errors) {
                                    var errors = jqXHR.responseJSON.errors;
                                    var errList = [];
                                    $.each(errors, function(field, msgs) {
                                        if ($.isArray(msgs)) {
                                            errList.push(msgs[0]);
                                        } else {
                                            errList.push(msgs);
                                        }
                                    });
                                    if (errList.length) msg = errList.join('<br>');
                                }
                            }
                            if (settings.useSwal && typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    html: msg,
                                    confirmButtonColor: '#2E8B57'
                                });
                            }
                            if (typeof settings.onError === 'function') {
                                settings.onError(jqXHR.responseJSON || {}, $form);
                            }
                        }
                    });
                }
            });
        });

        return this;
    };

    // ========================================================================
    // DOCUMENT READY — Initialize Everything
    // ========================================================================

    $(document).ready(function() {

        initPreloader();
        initNavbar();
        initScrollToTop();
        initAnimatedCounters();
        initCountdown();
        initCharts();
        initDataTables();
        initFormValidation();
        initAutoRefresh();
        initSearchModal();
        initTooltips();
        initConfirmDialogs();
        initFlashMessages();
        initFileUpload();
        initPasswordToggle();
        initSmoothScroll();
        initAOS();
        initYearSelector();
        initPulseUpdates();
        initThemeToggle();
        initSidebarToggle();
        initToastNotifications();
        initVoterInquiry();
        initScrollReveal();
        initDownloadTracking();

    });

})(jQuery);
