@extends('layouts.admin')
@section('title', 'Terminal - Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('styles')
<style>
    .terminal-wrap {
        border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow-lg);
        border: 1px solid rgba(255,255,255,0.08); background: #0d1117;
        display: flex; flex-direction: column; height: calc(100vh - 230px); min-height: 420px;
    }
    .terminal-header {
        background: linear-gradient(180deg, #161b22, #11151c); padding: 0.65rem 1rem;
        display: flex; align-items: center; gap: 10px; border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .terminal-header .dot { width: 12px; height: 12px; border-radius: 50%; display: inline-block; }
    .terminal-title {
        flex: 1; text-align: center; color: rgba(255,255,255,0.55); font-size: 0.75rem; font-weight: 600;
        letter-spacing: 0.5px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .terminal-body {
        flex: 1; overflow-y: auto; padding: 1rem 1.1rem; font-family: 'JetBrains Mono', 'Fira Code', 'SF Mono', Consolas, monospace;
        font-size: 0.82rem; line-height: 1.65; color: #c9d1d9; cursor: text;
    }
    .terminal-body::-webkit-scrollbar { width: 8px; }
    .terminal-body::-webkit-scrollbar-thumb { background: #30363d; border-radius: 8px; }
    .terminal-line { white-space: pre-wrap; word-break: break-word; }
    .terminal-line.prompt { color: #7ee787; }
    .terminal-line .path { color: #79c0ff; }
    .terminal-line.ok { color: #7ee787; }
    .terminal-line.err { color: #ff7b72; }
    .terminal-line.dim { color: #8b949e; }
    .terminal-input-row {
        display: flex; align-items: center; gap: 8px; padding: 0.7rem 1.1rem 0.9rem;
        border-top: 1px solid rgba(255,255,255,0.06); background: #0d1117;
    }
    .terminal-input-row .prompt-sign {
        color: #7ee787; font-family: 'JetBrains Mono', 'Fira Code', Consolas, monospace;
        font-size: 0.82rem; font-weight: 700; white-space: nowrap;
    }
    .terminal-input {
        flex: 1; background: transparent; border: none; outline: none; color: #e6edf3;
        font-family: 'JetBrains Mono', 'Fira Code', Consolas, monospace; font-size: 0.82rem; caret-color: #7ee787;
    }
    .terminal-input::placeholder { color: #484f58; }
    .terminal-cursor { width: 8px; height: 16px; background: #7ee787; display: inline-block; animation: blink 1s step-end infinite; }
    @keyframes blink { 50% { opacity: 0; } }
    .quick-chip {
        background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.25); color: #a5b4fc;
        font-size: 0.72rem; font-weight: 600; padding: 0.3rem 0.7rem; border-radius: 20px;
        cursor: pointer; transition: all 0.2s; white-space: nowrap; font-family: 'JetBrains Mono', Consolas, monospace;
    }
    .quick-chip:hover { background: rgba(99,102,241,0.3); color: #fff; transform: translateY(-1px); }
    .term-badge {
        font-size: 0.68rem; font-weight: 700; padding: 0.25rem 0.6rem; border-radius: 20px;
        background: rgba(239,68,68,0.12); color: #fca5a5; border: 1px solid rgba(239,68,68,0.25);
    }
    @media (max-width: 767.98px) {
        .terminal-wrap { height: calc(100vh - 200px); min-height: 380px; }
        .terminal-body { font-size: 0.75rem; padding: 0.8rem; }
    }
</style>
@endsection
@section('content')

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 animate-fade-up">
    <div>
        <h4 class="fw-bold mb-1 d-flex align-items-center gap-2" style="font-size:1.4rem">
            <i class="bi bi-terminal" style="color:var(--primary)"></i> Terminal Aplikasi
        </h4>
        <p class="text-muted mb-0 small">
            Konsol internal untuk admin — jalankan perintah <b>whitelist</b> (aman) langsung dari browser.
        </p>
    </div>
    <span class="term-badge"><i class="bi bi-shield-lock me-1"></i>AKSES: ADMIN LEVEL SAJA</span>
</div>

<div class="terminal-wrap animate-fade-up delay-1" id="terminalWrap">
    <div class="terminal-header">
        <span class="dot" style="background:#ff5f57"></span>
        <span class="dot" style="background:#febc2e"></span>
        <span class="dot" style="background:#28c840"></span>
        <span class="terminal-title">admin@panca-kota-tual: ~ / terminal</span>
        <button class="btn btn-sm p-0 border-0" onclick="clearTerminal()" title="Bersihkan layar (Ctrl+L)" style="background:transparent;color:#8b949e">
            <i class="bi bi-trash3"></i>
        </button>
    </div>
    <div class="terminal-body" id="terminalBody"></div>
    <form class="terminal-input-row" id="terminalForm" autocomplete="off">
        <span class="prompt-sign">admin@panca:~$</span>
        <input type="text" class="terminal-input" id="terminalInput"
               placeholder="Ketik perintah... (help untuk bantuan)" spellcheck="false" autofocus>
        <button type="submit" class="btn btn-sm p-0 border-0" id="terminalSubmit" style="background:transparent;color:#7ee787" title="Jalankan">
            <i class="bi bi-play-fill" style="font-size:1.1rem"></i>
        </button>
    </form>
</div>

{{-- Tombol perintah cepat --}}
<div class="d-flex flex-wrap gap-2 mt-3 animate-fade-up delay-2">
    @foreach($commands['builtins'] as $cmd => $desc)
        <button type="button" class="quick-chip" data-cmd="{{ $cmd }}" title="{{ $desc }}">
            <i class="bi bi-lightning-charge-fill me-1" style="font-size:0.65rem"></i>{{ $cmd }}
        </button>
    @endforeach
    @foreach($commands['artisan'] as $cmd => $desc)
        <button type="button" class="quick-chip" data-cmd="{{ $cmd }}" title="{{ $desc }}">{{ $cmd }}</button>
    @endforeach
</div>
<p class="text-muted mt-3 small animate-fade-up delay-3">
    <i class="bi bi-info-circle me-1"></i>
    Semua perintah yang dijalankan melalui terminal ini tercatat di <b>Activity Log</b>.
    Perintah di luar whitelist akan otomatis ditolak demi keamanan aplikasi.
</p>
@endsection

@push('scripts')
<script>
    (function () {
        const body   = document.getElementById('terminalBody');
        const form   = document.getElementById('terminalForm');
        const input  = document.getElementById('terminalInput');
        const submitBtn = document.getElementById('terminalSubmit');
        const RUN_URL = {{ json_encode(route('admin.terminal.run')) }};
        const USER_NAME = {{ json_encode(auth()->user()->name) }};
        const USER_ROLE = {{ json_encode(auth()->user()->role) }};

        let history = [];
        let histIndex = -1;
        let running = false;

        // ===== RENDER AMAN (plain text — anti XSS) =====
        function print(text, cls) {
            const div = document.createElement('div');
            div.className = 'terminal-line' + (cls ? ' ' + cls : '');
            div.textContent = text; // textContent = selalu escape
            body.appendChild(div);
            body.scrollTop = body.scrollHeight;
        }

        function printCommand(cmd) {
            const div = document.createElement('div');
            div.className = 'terminal-line prompt';
            const sign = document.createElement('span');
            sign.textContent = 'admin@panca:~$ ';
            div.appendChild(sign);
            const p = document.createElement('span');
            p.className = 'path';
            p.textContent = cmd;
            div.appendChild(p);
            body.appendChild(div);
            body.scrollTop = body.scrollHeight;
        }

        function banner() {
            print('┌─────────────────────────────────────────────────┐');
            print('│   PANGAN CERDAS (PANCA) — Maryadat Kota Tual    │');
            print('│   Terminal Internal • Khusus Admin Level        │');
            print('└─────────────────────────────────────────────────┘');
            print('Selamat datang, ' + USER_NAME + ' (role: ' + USER_ROLE + ')', 'ok');
            print('Ketik "help" untuk daftar perintah, "clear" untuk bersihkan layar.', 'dim');
            print('');
        }

        function clearTerminal() {
            body.innerHTML = '';
            print('Layar dibersihkan.', 'dim');
            print('');
            input.focus();
        }
        window.clearTerminal = clearTerminal;

        // ===== EKSEKUSI =====
        async function runCommand(cmd) {
            if (running || !cmd) return;
            running = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split" style="font-size:1rem"></i>';
            submitBtn.style.color = '#febc2e';

            printCommand(cmd);
            history.unshift(cmd);
            histIndex = -1;

            try {
                const res = await fetch(RUN_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ command: cmd }),
                });

                const data = await res.json();

                if (res.status === 419) {
                    print('❌ Sesi berakhir (CSRF). Muat ulang halaman lalu coba lagi.', 'err');
                } else if (res.status === 429) {
                    print('❌ Terlalu banyak perintah. Tunggu sebentar lalu coba lagi.', 'err');
                } else if (data.cleared) {
                    body.innerHTML = '';
                } else {
                    print(data.output || '(tanpa output)', data.ok ? '' : 'err');
                    if (data.ok && data.duration !== undefined) {
                        print('— selesai dalam ' + data.duration + ' ms —', 'dim');
                    }
                }
            } catch (e) {
                print('❌ Gagal terhubung ke server: ' + e.message, 'err');
            }

            print('');
            running = false;
            submitBtn.innerHTML = '<i class="bi bi-play-fill" style="font-size:1.1rem"></i>';
            submitBtn.style.color = '#7ee787';
            input.focus();
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const cmd = input.value.trim();
            input.value = '';
            if (cmd) runCommand(cmd);
        });

        // ===== RIWAYAT PERINTAH (↑ / ↓) + Ctrl+L =====
        input.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (histIndex < history.length - 1) { histIndex++; input.value = history[histIndex]; }
            } else if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (histIndex > 0) { histIndex--; input.value = history[histIndex]; }
                else { histIndex = -1; input.value = ''; }
            } else if (e.key === 'l' && e.ctrlKey) {
                e.preventDefault();
                clearTerminal();
            }
        });

        // Tombol perintah cepat
        document.querySelectorAll('.quick-chip').forEach(function (chip) {
            chip.addEventListener('click', function () {
                runCommand(chip.dataset.cmd);
            });
        });

        // Klik area terminal = fokus ke input
        document.getElementById('terminalWrap').addEventListener('click', function (e) {
            if (!e.target.closest('button')) input.focus();
        });

        banner();
        input.focus();
    })();
</script>
@endpush
