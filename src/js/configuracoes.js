// Configurações do usuário com suporte a AJAX e fallback para POST
class UserSettings {
  constructor() {
    this.currentUserId = window.currentUserId || 1
    this.useAjax = true // Flag para controlar se usa AJAX ou POST tradicional
    this.apiBasePath = "../api/" // Caminho correto baseado na estrutura mostrada
    this.init()
  }

  init() {
    // Initialize Lucide icons
    const lucide = window.lucide
    if (typeof lucide !== "undefined") {
      lucide.createIcons()
    }

    // Verificar se as APIs estão acessíveis
    this.checkApiAvailability()

    // Setup event listeners
    this.setupEventListeners()

    // Setup form validation
    this.setupFormValidation()
  }

  async checkApiAvailability() {
    try {
      console.log(`Testando caminho da API: ${this.apiBasePath}get_user.php`)
      const response = await fetch(`${this.apiBasePath}get_user.php?id=${this.currentUserId}`)

      if (response.ok) {
        const data = await response.json()
        if (data.success) {
          console.log("✅ APIs funcionando - Modo AJAX ativado")
          this.useAjax = true
          // Carregar dados do usuário
          this.loadUserData()
          return
        }
      }

      throw new Error("API não respondeu corretamente")
    } catch (error) {
      console.log("⚠️ APIs não acessíveis - Usando POST tradicional")
      console.error("Erro:", error.message)
      this.useAjax = false
      this.showToast("Modo compatibilidade ativado", "warning")
    }
  }

  async loadUserData() {
    if (!this.useAjax) return

    try {
      this.showLoading()
      const response = await fetch(`${this.apiBasePath}get_user.php?id=${this.currentUserId}`)

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()

      if (data.success && data.user) {
        const currentEmailInput = document.getElementById("current-email")
        if (currentEmailInput) {
          currentEmailInput.value = data.user.email || ""
        }
        console.log("Dados do usuário carregados com sucesso")
      } else {
        console.error("Erro na resposta da API:", data)
        this.showToast("Erro ao carregar dados do usuário", "error")
      }
    } catch (error) {
      console.error("Error loading user data:", error)
      this.showToast("Erro ao carregar dados do usuário", "error")
    } finally {
      this.hideLoading()
    }
  }

  setupEventListeners() {
    // Email form
    const emailForm = document.getElementById("email-form")
    if (emailForm) {
      emailForm.addEventListener("submit", (e) => {
        if (this.useAjax) {
          e.preventDefault()
          this.updateEmail()
        }
        // Se não usar AJAX, deixa o form ser submetido normalmente
      })
    }

    // Password form
    const passwordForm = document.getElementById("password-form")
    if (passwordForm) {
      passwordForm.addEventListener("submit", (e) => {
        if (this.useAjax) {
          e.preventDefault()
          this.updatePassword()
        }
        // Se não usar AJAX, deixa o form ser submetido normalmente
      })
    }

    // Password toggle buttons
    document.querySelectorAll(".toggle-password").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        this.togglePasswordVisibility(e.target.closest(".toggle-password"))
      })
    })

    // Delete account modal
    const deleteBtn = document.getElementById("delete-account-btn")
    if (deleteBtn) {
      deleteBtn.addEventListener("click", () => {
        this.showDeleteModal()
      })
    }

    const cancelBtn = document.getElementById("cancel-delete")
    if (cancelBtn) {
      cancelBtn.addEventListener("click", () => {
        this.hideDeleteModal()
      })
    }

    // Se usar AJAX, interceptar o botão de confirmar exclusão
    const confirmDeleteForm = document.getElementById("confirm-delete-form")
    if (confirmDeleteForm) {
      confirmDeleteForm.addEventListener("click", (e) => {
        if (this.useAjax) {
          e.preventDefault()
          this.deleteAccount()
        }
        // Se não usar AJAX, deixa o form ser submetido normalmente
      })
    }

    // Close modal on backdrop click
    const deleteModal = document.getElementById("delete-modal")
    if (deleteModal) {
      deleteModal.addEventListener("click", (e) => {
        if (e.target.id === "delete-modal") {
          this.hideDeleteModal()
        }
      })
    }

    // Logout rápido (se houver botão com classe logout-quick)
    const quickLogoutBtn = document.querySelector(".logout-quick")
    if (quickLogoutBtn) {
      quickLogoutBtn.addEventListener("click", (e) => {
        e.preventDefault()
        if (confirm("Tem certeza de que deseja sair?")) {
          this.quickLogout()
        }
      })
    }
  }

  setupFormValidation() {
    // Email validation
    const newEmailInput = document.getElementById("new-email")
    const emailSubmitBtn = document.querySelector("#email-form .btn-primary")

    if (newEmailInput && emailSubmitBtn) {
      newEmailInput.addEventListener("input", () => {
        emailSubmitBtn.disabled = !newEmailInput.value.trim()
      })
    }

    // Password validation
    const passwordInputs = ["current-password", "new-password", "confirm-password"]
    const passwordSubmitBtn = document.querySelector("#password-form .btn-primary")

    if (passwordSubmitBtn) {
      passwordInputs.forEach((id) => {
        const input = document.getElementById(id)
        if (input) {
          input.addEventListener("input", () => {
            const allFilled = passwordInputs.every((inputId) => {
              const el = document.getElementById(inputId)
              return el && el.value.trim()
            })
            passwordSubmitBtn.disabled = !allFilled
          })
        }
      })
    }
  }

  async updateEmail() {
    if (!this.useAjax) {
      console.log("AJAX não disponível, usando POST tradicional")
      return
    }

    const newEmailInput = document.getElementById("new-email")
    if (!newEmailInput) {
      this.showToast("Campo de email não encontrado", "error")
      return
    }

    const newEmail = newEmailInput.value.trim()

    if (!this.isValidEmail(newEmail)) {
      this.showToast("Por favor, insira um email válido", "error")
      return
    }

    try {
      this.showLoading()
      console.log(`Enviando requisição para: ${this.apiBasePath}update_email.php`)

      const response = await fetch(`${this.apiBasePath}update_email.php`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          user_id: this.currentUserId,
          new_email: newEmail,
        }),
      })

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()

      if (data.success) {
        const currentEmailInput = document.getElementById("current-email")
        if (currentEmailInput) {
          currentEmailInput.value = newEmail
        }
        newEmailInput.value = ""
        const submitBtn = document.querySelector("#email-form .btn-primary")
        if (submitBtn) submitBtn.disabled = true
        this.showToast("Email atualizado com sucesso!", "success")
      } else {
        this.showToast(data.message || "Erro ao atualizar email", "error")
      }
    } catch (error) {
      console.error("Error updating email:", error)
      this.showToast("Erro ao atualizar email. Verifique a conexão.", "error")
    } finally {
      this.hideLoading()
    }
  }

  async updatePassword() {
    if (!this.useAjax) {
      console.log("AJAX não disponível, usando POST tradicional")
      return
    }

    const currentPasswordInput = document.getElementById("current-password")
    const newPasswordInput = document.getElementById("new-password")
    const confirmPasswordInput = document.getElementById("confirm-password")

    if (!currentPasswordInput || !newPasswordInput || !confirmPasswordInput) {
      this.showToast("Campos de senha não encontrados", "error")
      return
    }

    const currentPassword = currentPasswordInput.value
    const newPassword = newPasswordInput.value
    const confirmPassword = confirmPasswordInput.value

    if (newPassword !== confirmPassword) {
      this.showToast("As senhas não coincidem", "error")
      return
    }

    if (newPassword.length < 6) {
      this.showToast("A nova senha deve ter pelo menos 6 caracteres", "error")
      return
    }

    try {
      this.showLoading()
      console.log(`Enviando requisição para: ${this.apiBasePath}update_password.php`)

      const response = await fetch(`${this.apiBasePath}update_password.php`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          user_id: this.currentUserId,
          current_password: currentPassword,
          new_password: newPassword,
        }),
      })

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()

      if (data.success) {
        // Clear form
        currentPasswordInput.value = ""
        newPasswordInput.value = ""
        confirmPasswordInput.value = ""
        const submitBtn = document.querySelector("#password-form .btn-primary")
        if (submitBtn) submitBtn.disabled = true
        this.showToast("Senha atualizada com sucesso!", "success")
      } else {
        this.showToast(data.message || "Erro ao atualizar senha", "error")
      }
    } catch (error) {
      console.error("Error updating password:", error)
      this.showToast("Erro ao atualizar senha. Verifique a conexão.", "error")
    } finally {
      this.hideLoading()
    }
  }

  async deleteAccount() {
    if (!this.useAjax) {
      console.log("AJAX não disponível, usando POST tradicional")
      return
    }

    try {
      this.showLoading()
      console.log(`Enviando requisição para: ${this.apiBasePath}delete_account.php`)

      const response = await fetch(`${this.apiBasePath}delete_account.php`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          user_id: this.currentUserId,
        }),
      })

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()

      if (data.success) {
        this.showToast("Conta excluída com sucesso", "success")
        setTimeout(() => {
          window.location.href = "../login/login.php"
        }, 2000)
      } else {
        this.showToast(data.message || "Erro ao excluir conta", "error")
      }
    } catch (error) {
      console.error("Error deleting account:", error)
      this.showToast("Erro ao excluir conta. Verifique a conexão.", "error")
    } finally {
      this.hideLoading()
      this.hideDeleteModal()
    }
  }

  async quickLogout() {
    try {
      this.showLoading()

      const response = await fetch("../logout/quick_logout.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
      })

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()

      if (data.success) {
        this.showToast("Logout realizado com sucesso!", "success")
        setTimeout(() => {
          window.location.href = data.redirect || "../login/login.php"
        }, 1500)
      } else {
        this.showToast(data.message || "Erro ao fazer logout", "error")
      }
    } catch (error) {
      console.error("Error during logout:", error)
      this.showToast("Erro ao fazer logout", "error")
    } finally {
      this.hideLoading()
    }
  }

  togglePasswordVisibility(button) {
    if (!button) return

    const targetId = button.getAttribute("data-target")
    const input = document.getElementById(targetId)
    const icon = button.querySelector("i")

    if (input && icon) {
      if (input.type === "password") {
        input.type = "text"
        icon.setAttribute("data-lucide", "eye-off")
      } else {
        input.type = "password"
        icon.setAttribute("data-lucide", "eye")
      }

      const lucide = window.lucide
      if (typeof lucide !== "undefined") {
        lucide.createIcons()
      }
    }
  }

  showDeleteModal() {
    const modal = document.getElementById("delete-modal")
    if (modal) {
      modal.classList.add("show")
    }
  }

  hideDeleteModal() {
    const modal = document.getElementById("delete-modal")
    if (modal) {
      modal.classList.remove("show")
    }
  }

  showLoading() {
    const loading = document.getElementById("loading")
    if (loading) {
      loading.classList.remove("hidden")
    }
  }

  hideLoading() {
    const loading = document.getElementById("loading")
    if (loading) {
      loading.classList.add("hidden")
    }
  }

  showToast(message, type = "success") {
    const container = document.getElementById("toast-container")
    if (!container) {
      console.log(`Toast: ${message} (${type})`)
      return
    }

    const toast = document.createElement("div")
    toast.className = `toast ${type}`
    toast.textContent = message

    // Adicionar estilos básicos se não existirem
    if (!toast.style.cssText) {
      toast.style.cssText = `
        padding: 12px 16px;
        margin: 8px 0;
        border-radius: 4px;
        color: white;
        font-weight: 500;
        opacity: 0;
        transition: opacity 0.3s ease;
        ${type === "success" ? "background-color: #10b981;" : ""}
        ${type === "error" ? "background-color: #ef4444;" : ""}
        ${type === "warning" ? "background-color: #f59e0b;" : ""}
      `
    }

    container.appendChild(toast)

    // Fade in
    setTimeout(() => {
      toast.style.opacity = "1"
    }, 100)

    // Fade out and remove
    setTimeout(() => {
      toast.style.opacity = "0"
      setTimeout(() => {
        if (toast.parentNode) {
          toast.remove()
        }
      }, 300)
    }, 4700)
  }

  isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return emailRegex.test(email)
  }
}

// Initialize the application
document.addEventListener("DOMContentLoaded", () => {
  console.log("Inicializando UserSettings...")
  new UserSettings()
})
