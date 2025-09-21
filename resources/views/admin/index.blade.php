@extends('admin.admin_dashboard')

@section('admin')
    <style>
        :root {
            --ink: #F4F7FF;
            --muted: #A9B6CE;
            --navy: #0F1B2D;
            --navy2: #16273F;
            --line: #E2EAF6;
            /* garis tabel */
            --text: #22324A;
            /* teks utama konten terang */

            /* KPI */
            --kpi1a: #4169E1;
            --kpi1b: #7C9BFF;
            --kpi2a: #6B46FF;
            --kpi2b: #9B7BFF;
            --kpi3a: #5D6C82;
            --kpi3b: #8FA2B9;
            --kpi4a: #2EB7B0;
            --kpi4b: #6FE6D9;
        }

        /* ===== HERO (jam) ===== */
        .hero {
            border-radius: 20px;
            padding: 22px;
            background: linear-gradient(135deg, var(--navy), var(--navy2));
            color: var(--ink);
            box-shadow: 0 12px 28px rgba(0, 0, 0, .22);
            margin-bottom: 18px
        }

        .hero-time {
            font-size: clamp(32px, 6vw, 56px);
            font-weight: 900;
            line-height: 1;
            /* gradient pada TEKS (ada putihnya) */
            background: linear-gradient(90deg, #ffffff 0%, #e9f6ff 40%, #6FE6D9 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hero-date {
            color: var(--muted);
            font-weight: 600
        }

        .hero-chips {
            display: flex;
            gap: 8px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 10px
        }

        .chip {
            font-size: .85rem;
            color: #2b3e5c;
            border: 1px solid #d6e2f3;
            background: #ffffffcc;
            padding: 6px 10px;
            border-radius: 999px;
            backdrop-filter: saturate(120%) blur(2px)
        }

        /* ===== GRID KPI ===== */
        .grid {
            display: grid;
            gap: 16px
        }

        @media(min-width:992px) {
            .grid-4 {
                grid-template-columns: repeat(4, 1fr)
            }
        }

        .stat {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 18px;
            border-radius: 18px;
            color: #fff;
            text-decoration: none;
            box-shadow: 0 12px 28px rgba(0, 0, 0, .22);
            transition: .25s ease;
            position: relative;
            overflow: hidden
        }

        .stat:hover {
            transform: translateY(-4px)
        }

        .stat:before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: radial-gradient(120% 100% at 100% 0, rgba(255, 255, 255, .22), transparent 60%);
            mix-blend-mode: soft-light
        }

        .stat .title {
            font-size: .82rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            font-weight: 800;
            opacity: .95
        }

        .stat .desc {
            font-size: .9rem;
            opacity: .9
        }

        .stat--item {
            background: linear-gradient(135deg, var(--kpi1a), var(--kpi1b))
        }

        .stat--emp {
            background: linear-gradient(135deg, var(--kpi2a), var(--kpi2b))
        }

        .stat--pinj {
            background: linear-gradient(135deg, var(--kpi3a), var(--kpi3b))
        }

        .stat--out {
            background: linear-gradient(135deg, var(--kpi4a), var(--kpi4b))
        }

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
            stroke: rgba(255, 255, 255, .28)
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

        /* ===== TABLE CLEAN (tanpa background) ===== */
        .section {
            margin-top: 18px
        }

        /* wrapper transparan */
        .table-wrap {
            overflow-x: auto
        }

        table.tclean {
            width: 100%;
            border-collapse: collapse
        }

        .tclean thead th {
            text-align: center;
            font-weight: 800;
            color: var(--text);
            padding: 12px 10px;
            border-bottom: 2px solid var(--line);
            background: transparent;
            /* tidak ada bg header */
        }

        .tclean tbody td {
            text-align: center;
            padding: 12px 10px;
            color: #3a4b66;
            border-bottom: 1px solid var(--line);
            background: transparent;
            /* tidak ada bg body */
        }

        .tclean tbody tr:hover td {
            background: #00000008
        }
    </style>

    <div class="page-content">
        <div class="container">

            {{-- HERO JAM --}}
            <div class="hero">
                <div class="text-center">
                    <div id="timenow" class="hero-time">00:00:00</div>
                    <div id="datenow" class="hero-date">–</div>
                    <div class="hero-chips">
                        <span id="chip-greet" class="chip">Hi!</span>
                        <span id="chip-week" class="chip">Week —</span>
                        <span id="chip-doy" class="chip">Day —/365</span>
                        <span id="chip-qt" class="chip">Q—</span>
                        <span id="chip-shift" class="chip">Shift —</span>
                    </div>
                </div>
            </div>

            {{-- KPI ROW (tetap) --}}
            <div class="grid grid-4">
                <div class="stat stat--item">
                    <div class="ring">
                        <svg viewBox="0 0 120 120">
                            <circle class="bg" cx="60" cy="60" r="54" fill="none" stroke-width="10" />
                            <circle class="fg" cx="60" cy="60" r="54" fill="none" stroke="#fff"
                                stroke-width="10" stroke-dasharray="339.29" stroke-dashoffset="339.29" />
                        </svg>
                        <div class="center"><span id="v-item">0</span><small>ITEM</small></div>
                    </div>
                    <div>
                        <div class="title">Item</div>
                        <div class="desc">Total item terdaftar</div>
                    </div>
                </div>

                <div class="stat stat--emp">
                    <div class="ring">
                        <svg viewBox="0 0 120 120">
                            <circle class="bg" cx="60" cy="60" r="54" fill="none" stroke-width="10" />
                            <circle class="fg" cx="60" cy="60" r="54" fill="none" stroke="#fff"
                                stroke-width="10" stroke-dasharray="339.29" stroke-dashoffset="339.29" />
                        </svg>
                        <div class="center"><span id="v-emp">0</span><small>EMP BORROW</small></div>
                    </div>
                    <div>
                        <div class="title">Employee (Borrow)</div>
                        <div class="desc">Karyawan meminjam</div>
                    </div>
                </div>

                <div class="stat stat--pinj">
                    <div class="ring">
                        <svg viewBox="0 0 120 120">
                            <circle class="bg" cx="60" cy="60" r="54" fill="none" stroke-width="10" />
                            <circle class="fg" cx="60" cy="60" r="54" fill="none" stroke="#fff"
                                stroke-width="10" stroke-dasharray="339.29" stroke-dashoffset="339.29" />
                        </svg>
                        <div class="center"><span id="v-pinj">0</span><small>PEMINJAMAN</small></div>
                    </div>
                    <div>
                        <div class="title">Peminjaman</div>
                        <div class="desc">Transaksi peminjaman</div>
                    </div>
                </div>

                <div class="stat stat--out">
                    <div class="ring">
                        <svg viewBox="0 0 120 120">
                            <circle class="bg" cx="60" cy="60" r="54" fill="none" stroke-width="10" />
                            <circle class="fg" cx="60" cy="60" r="54" fill="none" stroke="#fff"
                                stroke-width="10" stroke-dasharray="339.29" stroke-dashoffset="339.29" />
                        </svg>
                        <div class="center"><span id="v-out">0</span><small>ITEM OUT</small></div>
                    </div>
                    <div>
                        <div class="title">Item (Out)</div>
                        <div class="desc">Belum kembali</div>
                    </div>
                </div>
            </div>

            {{-- TABLE (tanpa background) --}}
            <div class="section">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                    <h6 style="margin:0;color:#243552">Peminjaman Hari ini</h6>
                    <div style="display:flex;gap:12px;flex-wrap:wrap;font-size:.86rem;color:#5a6e8e">
                        <span>• Dept</span><span>• Not Return</span>
                    </div>
                </div>
                <div class="table-wrap">
                    <table class="tclean">
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
                                <td id="put_not_return" style="font-weight:800;color:#129e8f">0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        // -------- Clock + extra info --------
        const pad = n => n < 10 ? '0' + n : n;

        function isoWeek(d) {
            const date = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()));
            const dayNum = date.getUTCDay() || 7;
            date.setUTCDate(date.getUTCDate() + 4 - dayNum);
            const yearStart = new Date(Date.UTC(date.getUTCFullYear(), 0, 1));
            return Math.ceil((((date - yearStart) / 86400000) + 1) / 7);
        }

        function dayOfYear(d) {
            const start = new Date(d.getFullYear(), 0, 0);
            return Math.floor((d - start) / 86400000);
        }

        function quarter(d) {
            return Math.floor(d.getMonth() / 3) + 1;
        }

        function shiftNow(h) {
            if (h >= 6 && h < 14) return 'Shift Pagi';
            if (h >= 14 && h < 22) return 'Shift Sore';
            return 'Shift Malam';
        }

        function greet(h) {
            if (h < 11) return 'Selamat pagi';
            if (h < 15) return 'Selamat siang';
            if (h < 18) return 'Selamat sore';
            return 'Selamat malam';
        }

        function tick() {
            const now = new Date();
            const h = now.getHours();
            document.getElementById('timenow').textContent =
                `${pad(h)}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
            document.getElementById('datenow').textContent =
                now.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });

            document.getElementById('chip-greet').textContent = `${greet(h)} 👋`;
            document.getElementById('chip-week').textContent = `Week ${isoWeek(now)}`;
            document.getElementById('chip-doy').textContent = `Day ${dayOfYear(now)}/365`;
            document.getElementById('chip-qt').textContent = `Q${quarter(now)}`;
            document.getElementById('chip-shift').textContent = shiftNow(h);
        }
        tick();
        setInterval(tick, 1000);

        // -------- Ring helpers --------
        const CIRC = 339.292;

        function countUp(el, to) {
            const dur = 600,
                start = performance.now(),
                from = Number(el.dataset.from || 0);

            function step(t) {
                const p = Math.min(1, (t - start) / dur),
                    n = Math.floor(from + (to - from) * p);
                el.textContent = n.toLocaleString('id-ID');
                if (p < 1) requestAnimationFrame(step);
                else el.dataset.from = to;
            }
            requestAnimationFrame(step);
        }

        function setShare(el, share) {
            const fg = el.closest('.ring').querySelector('.fg');
            const offset = CIRC * (1 - Math.max(0, Math.min(1, share || 0)));
            fg.style.strokeDashoffset = offset.toFixed(2);
        }

        // -------- Data fetchers (tetap seperti punyamu) --------
        function fetchTotalPeminjaman() {
            $.ajax({
                    url: "{{ route('get.peminjaman_today') }}",
                    method: "GET"
                })
                .done(function(res) {
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
                });
        }

        function fetchcountdepartment() {
            $.get("/get/peminjaman_department", function(r) {
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
            });
        }
        $(function() {
            fetchTotalPeminjaman();
            fetchcountdepartment();
            setInterval(fetchTotalPeminjaman, 30000);
            setInterval(fetchcountdepartment, 60000);
        });
    </script>
@endsection
