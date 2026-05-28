/**
 * Mail Utility Library
 * Simple wrapper for sending emails via the system API.
 */
const Mail = {
  /**
   * Sends an email using the centralized /mail endpoint.
   * 
   * @param {Object} options
   * @param {string} options.to - Recipient email address
   * @param {string} options.subject - Email subject
   * @param {string} options.body - Email HTML/Text body
   * @returns {Promise<Object>} Response from the server
   */
  send: async function ({ to, subject, body, bypass = false, lang = "" }) {
    const SITE_URL = document.querySelector('meta[name="site-url"]')?.content || "";

    if (!to || !subject || !body) {
      return {
        success: false,
        message: "Faltan parámetros requeridos: destinatario, asunto o cuerpo."
      };
    }

    const formData = new FormData();
    formData.append("to", to.trim());
    formData.append("subject", subject.trim());
    formData.append("body", body.trim());
    if (bypass) {
      formData.append("bypass", "true");
    }
    if (lang) {
      formData.append("lang", lang);
    }

    try {
      const response = await fetch(`${SITE_URL}/mail`, {
        method: "POST",
        body: formData
      });

      const data = await response.json();
      return data;
    } catch (error) {
      return {
        success: false,
        message: "Error de red o servidor al intentar enviar el correo."
      };
    }
  }
};
