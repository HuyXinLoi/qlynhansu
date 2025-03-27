document.addEventListener("DOMContentLoaded", () => {
  // Handle edit employee modal
  const editButtons = document.querySelectorAll(".edit-btn")
  if (editButtons) {
    editButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const id = this.getAttribute("data-id")
        const name = this.getAttribute("data-name")
        const gender = this.getAttribute("data-gender")
        const birthplace = this.getAttribute("data-birthplace")
        const dept = this.getAttribute("data-dept")
        const salary = this.getAttribute("data-salary")

        document.getElementById("edit_ma_nv").value = id
        document.getElementById("edit_ten_nv").value = name
        document.getElementById("edit_noi_sinh").value = birthplace
        document.getElementById("edit_ma_phong").value = dept
        document.getElementById("edit_luong").value = salary

        if (gender === "NAM") {
          document.getElementById("edit_gender_nam").checked = true
        } else {
          document.getElementById("edit_gender_nu").checked = true
        }
      })
    })
  }

  // Handle delete employee modal
  const deleteButtons = document.querySelectorAll(".delete-btn")
  if (deleteButtons) {
    deleteButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const id = this.getAttribute("data-id")
        const name = this.getAttribute("data-name")

        document.getElementById("delete_ma_nv").value = id
        document.getElementById("delete_employee_name").textContent = name
      })
    })
  }

  // Auto-hide alerts after 5 seconds
  const alerts = document.querySelectorAll(".alert")
  if (alerts) {
    alerts.forEach((alert) => {
      setTimeout(() => {
        alert.classList.add("fade")
        setTimeout(() => {
          alert.remove()
        }, 500)
      }, 5000)
    })
  }
})

