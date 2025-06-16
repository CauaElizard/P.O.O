// Configurações do usuário com suporte a AJAX e fallback para POST
class UserSettings {
  constructor() {
    this.currentUserId = window.currentUserId || 1
    this.useAjax = true // Flag para controlar se usa AJAX ou POST tradicional
    this.init()
  }

  init() {
    // Initialize Lucide icons
    const lucide = window.lucide // Declare the variable before using it
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
      const response = await fetch("api/get_user.php?id=" + this.currentUserId)
      if (response.ok) {
        const data = await response.json()
        if (data.success) {
          console.log("✅ APIs funcionando - Modo AJAX ativado")
          this.useAjax = true
          return
        }
      }
    } catch (error) {
      console.log("⚠️ APIs não acessíveis - Usando POST tradicional")
    }

    this.useAjax = false
    this.showToast("Modo compatibilidade ativado", "warning")
  }

  setupEventListeners() {
    // Email form
    document.getElementById("email-form").addEventListener("submit", (e) => {
      if (this.useAjax) {
        e.preventDefault()
        this.updateEmail()
      }
      // Se não usar AJAX, deixa o form ser submetido normalmente
    })

    // Password form
    document.getElementById("password-form").addEventListener("submit", (e) => {
      if (this.useAjax) {
        e.preventDefault()
        this.updatePassword()
      }
      // Se não usar AJAX, deixa o form ser submetido normalmente
    })

    // Password toggle buttons
    document.querySelectorAll(".toggle-password").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        this.togglePasswordVisibility(e.target.closest(".toggle-password"))
      })
    })

    // Delete account modal
    document.getElementById("delete-account-btn").addEventListener("click", () => {
      this.showDeleteModal()
    })

    document.getElementById("cancel-delete").addEventListener("click", () => {
      this.hideDeleteModal()
    })

    // Se usar AJAX, interceptar o botão de confirmar exclusão
    if (this.useAjax) {
      document.getElementById("confirm-delete-form").addEventListener("click", (e) => {
        e.preventDefault()
        this.deleteAccount()
      })
    }

    // Close modal on backdrop click
    document.getElementById("delete-modal").addEventListener("click", (e) => {
      if (e.target.id === "delete-modal") {
        this.hideDeleteModal()
      }
    })

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

    newEmailInput.addEventListener("input", () => {
      emailSubmitBtn.disabled = !newEmailInput.value.trim()
    })

    // Password validation
    const passwordInputs = ["current-password", "new-password", "confirm-password"]
    const passwordSubmitBtn = document.querySelector("#password-form .btn-primary")

    passwordInputs.forEach((id) => {
      document.getElementById(id).addEventListener("input", () => {
        const allFilled = passwordInputs.every((inputId) => document.getElementById(inputId).value.trim())
        passwordSubmitBtn.disabled = !allFilled
      })
    })
  }

  async updateEmail() {
    if (!this.useAjax) return

    const newEmail = document.getElementById("new-email").value.trim()

    if (!this.isValidEmail(newEmail)) {
      this.showToast("Por favor, insira um email válido", "error")
      return
    }

    try {
      this.showLoading()
      const response = await fetch("api/update_email.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          user_id: this.currentUserId,
          new_email: newEmail,
        }),
      })

      const data = await response.json()

      if (data.success) {
        document.getElementById("current-email").value = newEmail
        document.getElementById("new-email").value = ""
        document.querySelector("#email-form .btn-primary").disabled = true
        this.showToast("Email atualizado com sucesso!", "success")
      } else {
        this.showToast(data.message || "Erro ao atualizar email", "error")
      }
    } catch (error) {
      console.error("Error updating email:", error)
      this.showToast("Erro ao atualizar email", "error")
    } finally {
      this.hideLoading()
    }
  }

  async updatePassword() {
    if (!this.useAjax) return

    const currentPassword = document.getElementById("current-password").value
    const newPassword = document.getElementById("new-password").value
    const confirmPassword = document.getElementById("confirm-password").value

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
      const response = await fetch("api/update_password.php", {
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

      const data = await response.json()

      if (data.success) {
        // Clear form
        document.getElementById("current-password").value = ""
        document.getElementById("new-password").value = ""
        document.getElementById("confirm-password").value = ""
        document.querySelector("#password-form .btn-primary").disabled = true
        this.showToast("Senha atualizada com sucesso!", "success")
      } else {
        this.showToast(data.message || "Erro ao atualizar senha", "error")
      }
    } catch (error) {
      console.error("Error updating password:", error)
      this.showToast("Erro ao atualizar senha", "error")
    } finally {
      this.hideLoading()
    }
  }

  async deleteAccount() {
    if (!this.useAjax) return

    try {
      this.showLoading()
      const response = await fetch("api/delete_account.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          user_id: this.currentUserId,
        }),
      })

      const data = await response.json()

      if (data.success) {
        this.showToast("Conta excluída com sucesso", "success")
        setTimeout(() => {
          window.location.href = "login.php"
        }, 2000)
      } else {
        this.showToast(data.message || "Erro ao excluir conta", "error")
      }
    } catch (error) {
      console.error("Error deleting account:", error)
      this.showToast("Erro ao excluir conta", "error")
    } finally {
      this.hideLoading()
      this.hideDeleteModal()
    }
  }

  async quickLogout() {
    try {
      this.showLoading()

      const response = await fetch("quick_logout.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
      })

      const data = await response.json()

      if (data.success) {
        this.showToast("Logout realizado com sucesso!", "success")
        setTimeout(() => {
          window.location.href = data.redirect || "login.php"
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
    const targetId = button.getAttribute("data-target")
    const input = document.getElementById(targetId)
    const icon = button.querySelector("i")

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

  showDeleteModal() {
    document.getElementById("delete-modal").classList.add("show")
  }

  hideDeleteModal() {
    document.getElementById("delete-modal").classList.remove("show")
  }

  showLoading() {
    document.getElementById("loading").classList.remove("hidden")
  }

  hideLoading() {
    document.getElementById("loading").classList.add("hidden")
  }

  showToast(message, type = "success") {
    const toast = document.createElement("div")
    toast.className = `toast ${type}`
    toast.textContent = message

    document.getElementById("toast-container").appendChild(toast)

    setTimeout(() => {
      toast.remove()
    }, 5000)
  }

  isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return emailRegex.test(email)
  }
}

// Initialize the application
document.addEventListener("DOMContentLoaded", () => {
  new UserSettings()
})