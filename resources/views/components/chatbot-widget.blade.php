<div id="polbanChatbotWidget" class="chatbot-container">
    <!-- Floating Launcher Button -->
    <button id="chatbotLauncher" class="chatbot-launcher-btn shadow-lg" type="button" aria-label="Buka Chatbot Layanan">
        <i class="bi bi-chat-dots-fill text-white fs-3 transition-icon"></i>
        <i class="bi bi-x-lg text-white fs-3 transition-icon d-none"></i>
        <span class="position-absolute top-0 start-100 translate-middle p-1.5 bg-success border border-light rounded-circle">
            <span class="visually-hidden">Status Online</span>
        </span>
    </button>

    <!-- Chat Window Container -->
    <div id="chatbotWindow" class="chatbot-window shadow-xl d-none">
        <!-- Header -->
        <div class="chatbot-header bg-navy text-white p-3 d-flex align-items-center justify-content-between rounded-top-4">
            <div class="d-flex align-items-center">
                <div class="bg-white text-navy rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="bi bi-robot fs-5"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Virtual Assistant POLBAN</h6>
                    <small class="text-white-50 text-xs"><i class="bi bi-circle-fill text-success me-1" style="font-size: 8px;"></i> Aktif 24/7</small>
                </div>
            </div>
            <div class="d-flex items-center gap-1">
                <button id="chatbotResetBtn" class="btn btn-sm text-white opacity-75 hover-opacity-100 p-1" title="Reset Percakapan">
                    <i class="bi bi-arrow-counterclockwise fs-5"></i>
                </button>
                <button id="chatbotCloseBtn" class="btn btn-sm text-white opacity-75 hover-opacity-100 p-1" title="Tutup">
                    <i class="bi bi-x-lg fs-5"></i>
                </button>
            </div>
        </div>

        <!-- Body Messages -->
        <div id="chatbotBody" class="chatbot-body p-3 overflow-y-auto bg-light">
            <!-- Messages dynamic rendering -->
        </div>

        <!-- Footer / Nav Controls -->
        <div class="chatbot-footer bg-white border-top p-2 d-flex align-items-center justify-content-between text-xs">
            <button id="chatbotNavRoot" class="btn btn-link text-decoration-none text-navy p-1 text-xs fw-bold d-flex align-items-center">
                <i class="bi bi-house-door-fill me-1"></i> Menu Utama
            </button>
            <span class="text-muted text-xs opacity-75">Pusat Layanan POLBAN</span>
        </div>
    </div>
</div>

<style>
    .chatbot-container {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        z-index: 1080;
        font-family: inherit;
    }

    .chatbot-launcher-btn {
        width: 60px;
        height: 60px;
        border-radius: 30px;
        background: linear-gradient(135deg, #001f3f 0%, #003366 100%);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .chatbot-launcher-btn:hover {
        transform: scale(1.08);
        box-shadow: 0 0.8rem 2rem rgba(0, 31, 63, 0.35) !important;
    }

    .chatbot-window {
        position: absolute;
        bottom: 75px;
        right: 0;
        width: 360px;
        max-width: calc(100vw - 2rem);
        height: 520px;
        max-height: calc(100vh - 120px);
        background-color: #fff;
        border-radius: 1rem;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .chatbot-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        padding: 0.75rem;
        background-color: #ffffff;
    }

    .chat-bubble-bot {
        align-self: flex-start;
        max-width: 88%;
        background: #f5f7fa;
        color: #1e293b;
        border-radius: 1rem 1rem 1rem 0.2rem;
        padding: 0.6rem 0.85rem;
        font-size: 0.84rem;
        line-height: 1.45;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #cbd5e1;
    }

    .chat-bot-text {
        white-space: pre-line;
    }

    .chat-bubble-user {
        align-self: flex-end;
        max-width: 85%;
        background: #001f3f;
        color: #ffffff;
        border-radius: 1rem 1rem 0.2rem 1rem;
        padding: 0.6rem 0.85rem;
        font-size: 0.84rem;
        box-shadow: 0 2px 6px rgba(0,31,63,0.12);
        font-weight: 500;
    }

    .chat-cta-wrapper {
        margin-top: 1rem;
        padding-top: 0.85rem;
        border-top: 1px solid #cbd5e1;
    }

    .chat-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.9rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: #001f3f;
        background-color: #ffffff;
        border: 1px solid #001f3f;
        border-radius: 20px;
        text-decoration: none;
        transition: all 0.2s ease;
        width: fit-content;
        max-width: 100%;
        margin-top: 0.25rem;
    }

    .chat-cta-btn:hover {
        background-color: #001f3f;
        color: #ffffff;
        border-color: #001f3f;
        box-shadow: 0 2px 6px rgba(0, 31, 63, 0.15);
    }

    .chat-option-btn {
        text-align: left;
        font-size: 0.78rem;
        border-radius: 20px;
        padding: 0.35rem 0.75rem;
        border: 1px solid #001f3f;
        color: #001f3f;
        background-color: #ffffff;
        transition: all 0.2s ease;
        font-weight: 600;
        margin-top: 0.15rem;
    }

    .chat-option-btn:hover:not(:disabled) {
        background-color: #001f3f;
        color: #fff;
        transform: translateY(-1px);
    }

    .chat-option-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .bg-navy {
        background-color: #001f3f !important;
    }

    .text-navy {
        color: #001f3f !important;
    }

    .typing-indicator {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 8px 12px;
        background: #fff;
        border-radius: 12px;
        width: fit-content;
    }

    .typing-indicator span {
        width: 6px;
        height: 6px;
        background-color: #001f3f;
        border-radius: 50%;
        animation: typingBounce 1.4s infinite ease-in-out both;
    }

    .typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
    .typing-indicator span:nth-child(2) { animation-delay: -0.16s; }

    @keyframes typingBounce {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const launcher = document.getElementById('chatbotLauncher');
        const windowEl = document.getElementById('chatbotWindow');
        const closeBtn = document.getElementById('chatbotCloseBtn');
        const resetBtn = document.getElementById('chatbotResetBtn');
        const navRoot = document.getElementById('chatbotNavRoot');
        const bodyEl = document.getElementById('chatbotBody');
        const iconDots = launcher.querySelector('.bi-chat-dots-fill');
        const iconClose = launcher.querySelector('.bi-x-lg');

        let sessionToken = localStorage.getItem('polban_chatbot_session') || '';
        let isInitialized = false;
        let isProcessing = false;

        function toggleChat() {
            const isHidden = windowEl.classList.contains('d-none');
            if (isHidden) {
                windowEl.classList.remove('d-none');
                iconDots.classList.add('d-none');
                iconClose.classList.remove('d-none');
                if (!isInitialized) {
                    initChatbot();
                }
            } else {
                windowEl.classList.add('d-none');
                iconDots.classList.remove('d-none');
                iconClose.classList.add('d-none');
            }
        }

        launcher.addEventListener('click', toggleChat);
        closeBtn.addEventListener('click', toggleChat);
        resetBtn.addEventListener('click', () => {
            bodyEl.innerHTML = '';
            initChatbot();
        });
        navRoot.addEventListener('click', () => {
            selectNode(null, 'root');
        });

        function scrollToBottom() {
            setTimeout(() => {
                bodyEl.scrollTop = bodyEl.scrollHeight;
            }, 50);
        }

        function showTyping() {
            const typingHtml = `
                <div id="chatbotTyping" class="chat-bubble-bot shadow-none border-0 p-2">
                    <div class="typing-indicator">
                        <span></span><span></span><span></span>
                    </div>
                </div>
            `;
            bodyEl.insertAdjacentHTML('beforeend', typingHtml);
            scrollToBottom();
        }

        function removeTyping() {
            const typingEl = document.getElementById('chatbotTyping');
            if (typingEl) typingEl.remove();
        }

        function initChatbot() {
            showTyping();
            fetch("{{ route('chatbot.init') }}", {
                headers: {
                    'X-Chatbot-Session': sessionToken
                }
            })
            .then(res => res.json())
            .then(data => {
                removeTyping();
                if (data.session_token) {
                    sessionToken = data.session_token;
                    localStorage.setItem('polban_chatbot_session', sessionToken);
                }
                appendBotMessage(data.welcome_message, data.options);
                isInitialized = true;
            })
            .catch(err => {
                removeTyping();
                appendBotMessage('Maaf, sistem chatbot sedang mengalami gangguan. Silakan coba beberapa saat lagi.');
            });
        }

        function appendUserMessage(text) {
            const html = `<div class="chat-bubble-user">${escapeHtml(text)}</div>`;
            bodyEl.insertAdjacentHTML('beforeend', html);
            scrollToBottom();
        }

        function appendBotMessage(text, options = [], actionUrl = null, actionLabel = null, actionIcon = null, actionIconPos = 'left') {
            let optionsHtml = '';
            if (options && options.length > 0) {
                optionsHtml = '<div class="d-flex flex-column gap-1.5 mt-3 w-100">';
                options.forEach(opt => {
                    const iconTag = opt.icon ? `<i class="bi ${escapeHtml(opt.icon)} me-2"></i>` : '';
                    optionsHtml += `<button class="btn chat-option-btn" data-id="${opt.id}">${iconTag}${escapeHtml(opt.title)}</button>`;
                });
                optionsHtml += '</div>';
            }

            let ctaHtml = '';
            if (actionUrl) {
                let iconLeft = '';
                let iconRight = '';
                if (actionIcon) {
                    if (actionIconPos === 'right') {
                        iconRight = `<i class="bi ${escapeHtml(actionIcon)} ms-2"></i>`;
                    } else {
                        iconLeft = `<i class="bi ${escapeHtml(actionIcon)} me-2"></i>`;
                    }
                } else {
                    iconLeft = '<i class="bi bi-box-arrow-up-right me-2"></i>';
                }

                ctaHtml = `
                    <div class="chat-cta-wrapper">
                        <a href="${actionUrl}" target="_blank" class="chat-cta-btn">
                            ${iconLeft}${escapeHtml(actionLabel || 'Buka Link Halaman')}${iconRight}
                        </a>
                    </div>
                `;
            }

            const html = `
                <div class="chat-bubble-bot">
                    <div class="chat-bot-text">${formatMarkdown(text)}</div>
                    ${ctaHtml}
                    ${optionsHtml}
                </div>
            `;
            bodyEl.insertAdjacentHTML('beforeend', html);

            // Bind click events on newly appended option buttons
            const newButtons = bodyEl.querySelectorAll('.chat-option-btn:not([data-bound])');
            newButtons.forEach(btn => {
                btn.setAttribute('data-bound', 'true');
                btn.addEventListener('click', function() {
                    if (isProcessing) return;
                    const nodeId = this.getAttribute('data-id');
                    const titleText = this.innerText;

                    // Disable all current option buttons
                    bodyEl.querySelectorAll('.chat-option-btn').forEach(b => b.disabled = true);

                    appendUserMessage(titleText);
                    selectNode(nodeId);
                });
            });

            scrollToBottom();
        }

        function selectNode(nodeId, action = null) {
            if (isProcessing) return;
            isProcessing = true;
            showTyping();

            fetch("{{ route('chatbot.select') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Chatbot-Session': sessionToken
                },
                body: JSON.stringify({ node_id: nodeId, action: action })
            })
            .then(res => res.json())
            .then(data => {
                removeTyping();
                isProcessing = false;
                if (data.status === 'success') {
                    appendBotMessage(data.message, data.options, data.action_url, data.action_label, data.action_icon, data.action_icon_position);
                } else {
                    appendBotMessage(data.message || 'Maaf, terjadi kesalahan.');
                }
            })
            .catch(err => {
                removeTyping();
                isProcessing = false;
                appendBotMessage('Maaf, tidak dapat terhubung ke server.');
            });
        }

        function escapeHtml(str) {
            return String(str).replace(/[&<>"']/g, function(m) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
            });
        }

        function formatMarkdown(text) {
            if (!text) return '';
            let formatted = String(text).trim();
            formatted = escapeHtml(formatted);
            formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            formatted = formatted.replace(/(\r\n|\n|\r){3,}/g, '\n\n');
            return formatted;
        }
    });
</script>
