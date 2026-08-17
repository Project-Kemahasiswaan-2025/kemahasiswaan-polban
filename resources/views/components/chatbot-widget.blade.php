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

    .chat-doc-list {
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
        width: 100%;
        margin-top: 0.5rem;
    }

    .chat-doc-card {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.6rem;
        padding: 0.6rem 0.75rem;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        transition: all 0.2s ease;
        width: 100%;
    }

    .chat-doc-card:hover {
        border-color: #94a3b8;
        box-shadow: 0 3px 6px -1px rgba(0, 0, 0, 0.08);
    }

    .chat-doc-main {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        min-width: 0;
        flex: 1;
    }

    .chat-doc-icon {
        font-size: 1.25rem;
        line-height: 1.2;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .chat-doc-info {
        min-width: 0;
        flex: 1;
    }

    .chat-doc-name {
        font-weight: 700;
        font-size: 0.76rem;
        color: #0f172a;
        word-break: break-word;
        line-height: 1.35;
    }

    .chat-doc-meta {
        font-size: 0.65rem;
        color: #64748b;
        margin-top: 0.15rem;
    }

    .chat-doc-actions {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .chat-doc-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 0.375rem;
        font-size: 0.78rem;
        text-decoration: none !important;
        transition: all 0.15s ease;
        line-height: 1;
    }

    .chat-doc-btn.btn-preview {
        background-color: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
    }

    .chat-doc-btn.btn-preview:hover {
        background-color: #e2e8f0;
        color: #0f172a;
    }

    .chat-doc-btn.btn-download {
        background-color: #001f3f;
        color: #ffffff;
        border: 1px solid #001f3f;
    }

    .chat-doc-btn.btn-download:hover {
        background-color: #001429;
        color: #ffffff;
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
        resetBtn.addEventListener('click', function() {
            selectNode(null, 'root');
        });

        if (navRoot) {
            navRoot.addEventListener('click', function() {
                selectNode(null, 'root');
            });
        }

        function scrollToBottom() {
            setTimeout(() => {
                bodyEl.scrollTop = bodyEl.scrollHeight;
            }, 50);
        }

        function showTyping() {
            const html = `
                <div class="typing-indicator my-2" id="chatbotTyping">
                    <span></span><span></span><span></span>
                </div>
            `;
            bodyEl.insertAdjacentHTML('beforeend', html);
            scrollToBottom();
        }

        function removeTyping() {
            const el = document.getElementById('chatbotTyping');
            if (el) el.remove();
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

        function appendBotMessage(text, options = [], actionUrl = null, actionLabel = null, actionIcon = null, actionIconPos = 'left', documents = []) {
            let optionsHtml = '';
            if (options && options.length > 0) {
                optionsHtml = '<div class="d-flex flex-column gap-1.5 mt-3 w-100">';
                options.forEach(opt => {
                    const iconTag = opt.icon ? `<i class="bi ${escapeHtml(opt.icon)} me-2"></i>` : '';
                    const actionType = opt.action_type || '';
                    const moduleKey = opt.module_key || '';
                    const moduleParam = opt.module_param || '';
                    optionsHtml += `<button class="btn chat-option-btn" data-id="${opt.id}" data-action="${actionType}" data-module="${moduleKey}" data-param="${moduleParam}">${iconTag}${escapeHtml(opt.title)}</button>`;
                });
                optionsHtml += '</div>';
            }

            let docsHtml = '';
            if (documents && documents.length > 0) {
                docsHtml = '<div class="chat-doc-list">';
                documents.forEach(doc => {
                    let iconClass = 'bi-file-earmark-text text-secondary';
                    if (doc.file_type === 'PDF') iconClass = 'bi-file-earmark-pdf-fill text-danger';
                    else if (doc.file_type === 'IMG') iconClass = 'bi-file-earmark-image-fill text-primary';
                    else if (doc.file_type === 'DOCX') iconClass = 'bi-file-earmark-word-fill text-primary';
                    else if (doc.file_type === 'XLSX') iconClass = 'bi-file-earmark-excel-fill text-success';

                    let previewBtn = doc.can_preview ? `
                        <a href="${doc.preview_url}" target="_blank" class="chat-doc-btn btn-preview" title="Pratinjau Dokumen">
                            <i class="bi bi-eye"></i>
                        </a>
                    ` : '';

                    docsHtml += `
                        <div class="chat-doc-card">
                            <div class="chat-doc-main">
                                <div class="chat-doc-icon"><i class="bi ${iconClass}"></i></div>
                                <div class="chat-doc-info">
                                    <div class="chat-doc-name">${escapeHtml(doc.name)}</div>
                                    <div class="chat-doc-meta">${doc.file_type}${doc.file_size_formatted ? ' &bull; ' + doc.file_size_formatted : ''}</div>
                                </div>
                            </div>
                            <div class="chat-doc-actions">
                                ${previewBtn}
                                <a href="${doc.download_url}" target="_blank" class="chat-doc-btn btn-download" title="Unduh Dokumen">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        </div>
                    `;
                });
                docsHtml += '</div>';
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
                    ${docsHtml}
                    ${ctaHtml}
                    ${optionsHtml}
                </div>
            `;
            bodyEl.insertAdjacentHTML('beforeend', html);

            const newButtons = bodyEl.querySelectorAll('.chat-option-btn:not([data-bound])');
            newButtons.forEach(btn => {
                btn.setAttribute('data-bound', 'true');
                btn.addEventListener('click', function() {
                    if (isProcessing) return;
                    const nodeId = this.getAttribute('data-id');
                    const action = this.getAttribute('data-action');
                    const moduleKey = this.getAttribute('data-module');
                    const moduleParam = this.getAttribute('data-param');
                    const titleText = this.innerText;

                    bodyEl.querySelectorAll('.chat-option-btn').forEach(b => b.disabled = true);

                    appendUserMessage(titleText);
                    selectNode(nodeId, action, moduleKey, moduleParam);
                });
            });

            scrollToBottom();
        }

        function selectNode(nodeId, action = null, moduleKey = null, moduleParam = null) {
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
                body: JSON.stringify({
                    node_id: nodeId,
                    action: action,
                    module_key: moduleKey,
                    module_param: moduleParam
                })
            })
            .then(res => res.json())
            .then(data => {
                removeTyping();
                isProcessing = false;
                if (data.status === 'success') {
                    appendBotMessage(data.message, data.options, data.action_url, data.action_label, data.action_icon, data.action_icon_position, data.documents);
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

            // Inline code `...`
            formatted = formatted.replace(/`([^`]+)`/g, '<code class="bg-light text-dark px-1 py-0.5 rounded border" style="font-size: 11px;">$1</code>');
            // Headers ### **Header**
            formatted = formatted.replace(/### \*\*(.*?)\*\*/g, '<strong class="d-block text-navy fs-6 mb-1">$1</strong>');
            // Bold **text**
            formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            // Markdown links [text](url)
            formatted = formatted.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank" class="text-primary text-decoration-underline font-weight-bold">$1</a>');

            formatted = formatted.replace(/(\r\n|\n|\r){3,}/g, '\n\n');
            return formatted;
        }
    });
</script>
