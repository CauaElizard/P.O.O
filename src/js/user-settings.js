// Import Lucide icons library
import lucide from "lucide"

class UserSettings {
  constructor() {
    this.currentUserId = 1 // Simulated user ID - in real app, get from session
    this.init()
  }

  init() {
    // Initialize Lucide icons
    lucide.createIcons()

    // Load current user data
    this.loadUserData()

    // Setup event listeners
    this.setupEventListeners()

    // Setup form validation
    this.setupFormValidation()
  }

  setupEventListeners() {
    // Email form
    document.getElementById("email-form").addEventListener("submit", (e) => {
      e.preventDefault()
      this.updateEmail()
    })

    // Password form
    document.getElementById("password-form").addEventListener("submit", (e) => {
      e.preventDefault()
      this.updatePassword()
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

    document.getElementById("confirm-delete").addEventListener("click", () => {
      this.deleteAccount()
    })

    // Close modal on backdrop click
    document.getElementById("delete-modal").addEventListener("click", (e) => {
      if (e.target.id === "delete-modal") {
        this.hideDeleteModal()
      }
    })
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

  async loadUserData() {
    try {
      this.showLoading()
      const response = await fetch(`public/api/get_user.php?id=${this.currentUserId}`)
      const data = await response.json()

      if (data.success) {
        document.getElementById("current-email").value = data.user.email
      } else {
        this.showToast("Erro ao carregar dados do usuário", "error")
      }
    } catch (error) {
      console.error("Error loading user data:", error)
      this.showToast("Erro ao carregar dados do usuário", "error")
    } finally {
      this.hideLoading()
    }
  }

  async updateEmail() {
    const newEmail = document.getElementById("new-email").value.trim()

    if (!this.isValidEmail(newEmail)) {
      this.showToast("Por favor, insira um email válido", "error")
      return
    }

    try {
      this.showLoading()
      const response = await fetch("public/api/update_email.php", {
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
      const response = await fetch("public/api/update_password.php", {
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
    try {
      this.showLoading()
      const response = await fetch("public/api/delete_account.php", {
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
        // Redirect to login or home page
        setTimeout(() => {
          window.location.href = "login.html"
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

    lucide.createIcons()
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
