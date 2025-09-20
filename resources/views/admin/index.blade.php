@extends('admin.admin_dashboard')

@section('admin')
    <style>
        /* ===== Theme ===== */
        :root {
            --ink: #F4F7FF;
            --muted: #A9B6CE;
            --navy: #0F1B2D;
            --navy2: #182B46;
            --shadow: 0 12px 28px rgba(0, 0, 0, .22);

            /* accents KPI */
            --item1: #FF416C;
            --item2: #FF6A88;
            /* ITEM */
            --emp1: #4A62F7;
            --emp2: #6E85FF;
            /* EMP BORROW */
            --pinj1: #6C7A8C;
            --pinj2: #8D9AAF;
            /* PEMINJAMAN */
            --out1: #26B0A5;
            --out2: #68E6D9;
            /* ITEM OUT */
        }

        /* ===== Hero ===== */
        .hero {
            border-radius: 18px;
            padding: 22px;
            background: linear-gradient(135deg, var(--navy), var(--navy2));
            color: var(--ink);
            box-shadow: var(--shadow);
            margin-bottom: 18px;
        }

        .hero-time {
            font-size: clamp(32px, 6vw, 56px);
            font-weight: 900;
            line-height: 1
        }

        .hero-date {
            color: var(--muted);
            font-weight: 600
        }

        /* ===== Grid ===== */
        .grid {
            display: grid;
            gap: 16px
        }

        @media(min-width:992px) {
            .grid-4 {
                grid-template-columns: repeat(4, 1fr)
            }
        }

        /* ===== KPI cards (with ring) ===== */
        .stat {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 18px;
            border-radius: 16px;
            color: #fff;
            text-decoration: none;
            box-shadow: var(--shadow);
            transition: transform .25s ease, box-shadow .25s ease;
            position: relative;
            overflow: hidden;
        }

        .stat:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 36px rgba(0, 0, 0, .28)
        }

        .stat .title {
            font-size: .82rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            font-weight: 800;
            opacity: .95
        }

        .stat .value {
            font-weight: 900;
            font-size: 38px;
            line-height: 1
        }

        .stat .desc {
            font-size: .9rem;
            opacity: .85
        }

        .stat--item {
            background: linear-gradient(135deg, var(--item1), var(--item2))
        }

        .stat--emp {
            background: linear-gradient(135deg, var(--emp1), var(--emp2))
        }

        .stat--pinj {
            background: linear-gradient(135deg, var(--pinj1), var(--pinj2))
        }

        .stat--out {
            background: linear-gradient(135deg, var(--out1), var(--out2))
        }

        /* ring */
        .ring {
            position: relative;
            width: 108px;
            height: 108px;
            flex: 0 0 auto
        }

        .ring svg {
            width: 108px;
            height: 108px
        }

        .ring .bg {
            stroke: rgba(255, 255, 255, .35)
        }

        .ring .fg {
            stroke-linecap: round;
            transition: stroke-dashoffset .9s ease
        }

        .ring .center {
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center
        }

        .ring .center span {
            font-weight: 900;
            font-size: 24px
        }

        .ring .center small {
            display: block;
            font-size: 10px;
            letter-spacing: .08em;
            opacity: .9
        }

        /* ===== Table card ===== */
        .card-soft {
            background: #0e1726;
            background: linear-gradient(180deg, #0f1b2d 0%, #132640 100%);
            border-radius: 16px;
            padding: 14px;
            box-shadow: var(--shadow);
            color: #e7eeff;
        }

        .card-soft h6 {
            margin: 6px 8px 12px;
            opacity: .9
        }

        .table-wrap {
            overflow-x: auto;
        }

        table.tmodern {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: .95rem
        }

        .tmodern thead th {
            position: sticky;
            top: 0;
            z-index: 1;
            color: #fff;
            text-align: center;
            font-weight: 800;
            background: linear-gradient(135deg, #275b8f, #2f80a1);
            padding: 10px 8px;
        }

        .tmodern tbody td {
            text-align: center;
            padding: 10px 8px;
            background: rgba(255, 255, 255, .04)
        }

        .tmodern tbody tr:nth-child(even) td {
            background: rgba(255, 255, 255, .06)
        }

        .tmodern tbody tr:hover td {
            background: rgba(255, 255, 255, .10)
        }

        /* small helpers */
        .fadeUp {
            transform: translateY(12px);
            opacity: 0;
            animation: fadeUp .6s ease forwards
        }

        @keyframes fadeUp {
            to {
                transform: translateY(0);
                opacity: 1
            }
        }

        .pulse {
            animation: pulse .4s ease
        }

        @keyframes pulse {
            50% {
                transform: scale(1.06)
            }
        }

        /* skeleton */
        .skeleton {
            height: 36px;
            border-radius: 8px;
            background: rgba(255, 255, 255, .25);
            overflow: hidden
        }

        .skeleton:after {
            content: "";
            position: absolute;
            inset: 0;
            transform: translateX(-100%);
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .5), transparent);
            animation: shimmer 1.2s infinite
        }

        @keyframes shimmer {
            to {
                transform: translateX(100%)
            }
        }
    </style>

    <div class="page-content">
        <div class="container">

            {{-- HERO: Time & Date --}}
            <div class="hero fadeUp" style="animation-delay:.02s">
                <div class="text-center">
                    <div id="timenow" class="hero-time">00:00:00</div>
                    <div id="datenow" class="hero-date">–</div>
                </div>
            </div>

            {{-- KPI ROW --}}
            <div class="grid grid-4">
                {{-- ITEM --}}
                <div class="stat stat--item fadeUp" style="animation-delay:.08s">
                    <div class="ring">
                        <svg viewBox="0 0 120 120" aria-hidden="true">
                            <circle class="bg" cx="60" cy="60" r="54" fill="none" stroke-width="10" />
                            <circle class="fg" cx="60" cy="60" r="54" fill="none" stroke="#fff"
                                stroke-width="10" stroke-dasharray="339.29" stroke-dashoffset="339.29" />
                        </svg>
                        <div class="center">
                            <span id="v-item">0</span>
                            <small>ITEM</small>
                        </div>
                    </div>
                    <div>
                        <div class="title">Item</div>
                        <div class="desc">Total item terdaftar</div>
                    </div>
                </div>

                {{-- EMP BORROW --}}
                <div class="stat stat--emp fadeUp" style="animation-delay:.12s">
                    <div class="ring">
                        <svg viewBox="0 0 120 120" aria-hidden="true">
                            <circle class="bg" cx="60" cy="60" r="54" fill="none" stroke-width="10" />
                            <circle class="fg" cx="60" cy="60" r="54" fill="none" stroke="#fff"
                                stroke-width="10" stroke-dasharray="339.29" stroke-dashoffset="339.29" />
                        </svg>
                        <div class="center">
                            <span id="v-emp">0</span>
                            <small>EMP BORROW</small>
                        </div>
                    </div>
                    <div>
                        <div class="title">Employee (Borrow)</div>
                        <div class="desc">Karyawan meminjam</div>
                    </div>
                </div>

                {{-- PEMINJAMAN --}}
                <div class="stat stat--pinj fadeUp" style="animation-delay:.16s">
                    <div class="ring">
                        <svg viewBox="0 0 120 120" aria-hidden="true">
                            <circle class="bg" cx="60" cy="60" r="54" fill="none" stroke-width="10" />
                            <circle class="fg" cx="60" cy="60" r="54" fill="none" stroke="#fff"
                                stroke-width="10" stroke-dasharray="339.29" stroke-dashoffset="339.29" />
                        </svg>
                        <div class="center">
                            <span id="v-pinj">0</span>
                            <small>PEMINJAMAN</small>
                        </div>
                    </div>
                    <div>
                        <div class="title">Peminjaman</div>
                        <div class="desc">Transaksi peminjaman</div>
                    </div>
                </div>

                {{-- ITEM OUT --}}
                <div class="stat stat--out fadeUp" style="animation-delay:.20s">
                    <div class="ring">
                        <svg viewBox="0 0 120 120" aria-hidden="true">
                            <circle class="bg" cx="60" cy="60" r="54" fill="none" stroke-width="10" />
                            <circle class="fg" cx="60" cy="60" r="54" fill="none" stroke="#fff"
                                stroke-width="10" stroke-dasharray="339.29" stroke-dashoffset="339.29" />
                        </svg>
                        <div class="center">
                            <span id="v-out">0</span>
                            <small>ITEM OUT</small>
                        </div>
                    </div>
                    <div>
                        <div class="title">Item (Out)</div>
                        <div class="desc">Belum kembali</div>
                    </div>
                </div>
            </div>

            {{-- TABLE SECTION --}}
            <div class="card-soft fadeUp" style="margin-top:18px; animation-delay:.26s">
                <h6>Peminjaman Hari ini</h6>
                <div class="table-wrap">
                    <table class="tmodern">
                        <thead>
                            <tr>
                                <th>SEW</th>
                                <th>QC</th>
                                <th>PACK</th>
                                <th>CUTT</th>
                                <th>MEK</th>
                                <th>SPL</th>
                                <th>WH</th>
                                <th>FOLD</th>
                                <th>PRINT</th>
                                <th>IRON</th>
                                <th>OTHER</th>
                                <th>NOT RETURN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="put_sewing">0</td>
                                <td id="put_qc">0</td>
                                <td id="put_packing">0</td>
                                <td id="put_cutting">0</td>
                                <td id="put_mekanik">0</td>
                                <td id="put_sample">0</td>
                                <td id="put_wh">0</td>
                                <td id="put_folding">0</td>
                                <td id="put_print">0</td>
                                <td id="put_iron">0</td>
                                <td id="put_other">0</td>
                                <td id="put_not_return">0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        // ===== Clock =====
        function pad(n) {
            return n < 10 ? '0' + n : n
        }

        function tick() {
            const now = new Date();
            document.getElementById('timenow').textContent =
                `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
            document.getElementById('datenow').textContent =
                now.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
        }
        tick();
        setInterval(tick, 1000);

        // ===== Count-up & Ring =====
        const CIRC = 339.292; // 2πr (r=54)
        function countUp(el, to) {
            const dur = 600,
                start = performance.now(),
                from = Number(el.dataset.from || 0);

            function step(t) {
                const p = Math.min(1, (t - start) / dur),
                    n = Math.floor(from + (to - from) * p);
                el.textContent = n.toLocaleString('id-ID');
                if (p < 1) requestAnimationFrame(step);
                else el.dataset.from = to, el.classList.add('pulse'), setTimeout(() => el.classList.remove('pulse'), 300);
            }
            requestAnimationFrame(step);
        }

        function setShare(el, share) {
            const fg = el.closest('.ring').querySelector('.fg');
            const offset = CIRC * (1 - Math.max(0, Math.min(1, share || 0)));
            fg.style.strokeDashoffset = offset.toFixed(2);
        }

        // ===== Data fetchers =====
        function fetchTotalPeminjaman() {
            $.ajax({
                url: "{{ route('get.peminjaman_today') }}",
                method: "GET"
            }).done(function(res) {
                const d = res?.data || {};
                const vItem = Number(d.ITEM || 0),
                    vEmp = Number(d.EMPLOYEE_BORROW || 0),
                    vPinj = Number(d.PEMINJAMAN || 0),
                    vOut = Number(d.ITEM_OUT || 0);
                const total = Math.max(1, vItem + vEmp + vPinj + vOut);

                countUp(document.getElementById('v-item'), vItem);
                setShare(document.getElementById('v-item'), vItem / total);
                countUp(document.getElementById('v-emp'), vEmp);
                setShare(document.getElementById('v-emp'), vEmp / total);
                countUp(document.getElementById('v-pinj'), vPinj);
                setShare(document.getElementById('v-pinj'), vPinj / total);
                countUp(document.getElementById('v-out'), vOut);
                setShare(document.getElementById('v-out'), vOut / total);
            }).fail(function(err) {
                console.error('Err total:', err)
            });
        }

        function fetchcountdepartment() {
            $.ajax({
                url: "/get/peminjaman_department",
                method: "GET"
            }).done(function(r) {
                if (!r?.success) return;
                const d = r.data || {};
                $('#put_sewing').text(d.SEW || 0);
                $('#put_qc').text(d.QC || 0);
                $('#put_packing').text(d.PACK || 0);
                $('#put_cutting').text(d.CUTT || 0);
                $('#put_mekanik').text(d.MEK || 0);
                $('#put_sample').text(d.SPL || 0);
                $('#put_wh').text(d.WH || 0);
                $('#put_folding').text(d.FOLD || 0);
                $('#put_print').text(d.PRINT || 0);
                $('#put_iron').text(d.IRON || 0);
                $('#put_other').text(d.OTHER || 0);
                $('#put_not_return').text(d.NOT_RETURN || 0);
            }).fail(function(err) {
                console.error('Err dept:', err)
            });
        }

        $(function() {
            fetchTotalPeminjaman();
            fetchcountdepartment();
            // refresh berkala
            setInterval(fetchTotalPeminjaman, 30000);
            setInterval(fetchcountdepartment, 60000);
        });
    </script>
@endsection
