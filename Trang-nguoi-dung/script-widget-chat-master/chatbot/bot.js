(function (config) {
  if (document.getElementById("chatContainer")) return;

  let chatStyle = document.createElement("link");
  chatStyle.rel = "stylesheet";
  chatStyle.href = "https://cdn.jsdelivr.net/npm/@n8n/chat/dist/style.css";
  document.head.appendChild(chatStyle);

  let chatContainer = document.createElement("div");
  chatContainer.id = "chatContainer";
  document.body.appendChild(chatContainer);

  let customStyle = document.createElement("style");
  customStyle.innerHTML = `
  :root {
    --chat--color-primary: #7b3fe4;
    --chat--color-primary-hover: #6633cc;
    --chat--color-primary-active: #5028aa;
    --chat--color-white: #ffffff;
    --chat--color-light: #f4f6fb;
    --chat--color-dark: #1f1f2e;
    --chat--border-radius: 14px;
    --chat--window--width: 380px;
    --chat--window--height: 540px;
    --chat--toggle--size: 50px;
    --chat--font-size: 14px;
  }

  .chat-window-wrapper {
    bottom: 1.8rem;
    right: 1.8rem;
    font-family: 'Segoe UI', 'Inter', sans-serif;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    border-radius: var(--chat--border-radius);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    border: 1px solid rgba(120, 120, 120, 0.08);
    backdrop-filter: blur(8px);
  }

  #chatContainer .chat-heading {
    font-size: 12px;
    padding: 4px 10px;
    height: 26px;
    background: linear-gradient(to right, #4A00E0, #8E2DE2);
    color: var(--chat--color-white);
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-radius: var(--chat--border-radius);
  }
  

  #chatContainer .chat-heading::before {
    content: "🤖";
    width: 22px;
    height: 22px;
    font-size: 14px;
    background-color: #fff;
    color: var(--chat--color-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
  }

  #chatContainer .chat-body {
    flex: 1;
    background: var(--chat--color-light);
    padding: 12px;
    overflow-y: auto;
  }

  #chatContainer .chat-message {
    padding: 8px 12px;
    font-size: var(--chat--font-size);
    border-radius: var(--chat--border-radius);
    margin-bottom: 8px;
    background: #ffffff;
    color: #333;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    max-width: 90%;
    line-height: 1.4;
    transition: all 0.2s ease;
  }

  #chatContainer .chat-message:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  }

  #chatContainer .chat-input {
    padding: 10px;
    border-top: 1px solid #e5e7eb;
    background: #ffffff;
    display: flex;
    gap: 8px;
  }

  #chatContainer .chat-input input,
  #chatContainer .chat-input textarea {
    flex: 1;
    background-color: #f9fafb;
    color: #111;
    border: 1px solid #d1d5db;
    padding: 10px 12px;
    font-size: 10px;
    border-radius: 10px;
    outline: none;
    transition: border 0.2s ease;
  }

  #chatContainer .chat-input input:focus,
  #chatContainer .chat-input textarea:focus {
    border-color: var(--chat--color-primary);
  }

  #chatContainer .chat-input button {
    background: var(--chat--color-primary);
    border: none;
    color: white;
    padding: 10px 14px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 10px;
    cursor: pointer;
    transition: background 0.2s, transform 0.1s ease;
  }

  #chatContainer .chat-input button:hover {
    background: var(--chat--color-primary-hover);
  }

  #chatContainer .chat-input button:active {
    transform: scale(0.97);
    background: var(--chat--color-primary-active);
  }

  #chatContainer .chat-toggle {
    width: var(--chat--toggle--size);
    height: var(--chat--toggle--size);
    background-color: var(--chat--color-primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.25);
    transition: background-color 0.3s, transform 0.2s ease;
  }

  #chatContainer .chat-toggle:hover {
    background-color: var(--chat--color-primary-hover);
    transform: scale(1.05);
  }

  #chatContainer .chat-toggle:active {
    background-color: var(--chat--color-primary-active);
  }
  `;
  document.head.appendChild(customStyle);

  let chatScript = document.createElement("script");
  chatScript.type = "module";
  chatScript.src = "https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js";
  chatScript.onload = function () {
    import("https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js").then(({ createChat }) => {
      createChat({
        mode: "window",
        target: "#chatContainer",
        webhookUrl: config.webhookUrl,
        showWelcomeScreen: false,
        loadPreviousSession: false,
        allowFileUploads: false,
        allowedFilesMimeTypes: "*",
        i18n: {
          en: {
            title: config.title || "Trợ lý AI VIP ✨",
            subtitle: config.subtitle || "Chat với tôi bất cứ lúc nào!",
            inputPlaceholder: "Nhập tin nhắn của bạn...",
          },
        },
        initialMessages: [config.welcomeBot, config.messageBot],
      });
    });
  };

  document.body.appendChild(chatScript);
})({
  title: "Membee Chat",
  subtitle: "Chatbot hỗ trợ 24/7",
  webhookUrl: "",
  welcomeBot: "",
  messageBot: ""
});
