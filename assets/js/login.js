// Função para mostrar/ocultar senha
function togglePassword() {
    const senhaInput = document.getElementById('senha');
    const eyeIcon = document.getElementById('eyeIcon');
    
    if (senhaInput.type === 'password') {
      senhaInput.type = 'text';
      eyeIcon.classList.remove('fa-eye');
      eyeIcon.classList.add('fa-eye-slash');
    } else {
      senhaInput.type = 'password';
      eyeIcon.classList.remove('fa-eye-slash');
      eyeIcon.classList.add('fa-eye');
    }
  }

  // Validação do formulário
  document.getElementById('loginForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value.trim();
    const senha = document.getElementById('senha').value.trim();
    
    if (!email || !senha) {
      e.preventDefault();
      alert('Por favor, preencha todos os campos.');
      return false;
    }
    
    // Validação básica de email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      e.preventDefault();
      alert('Por favor, insira um email válido.');
      return false;
    }
  });

  // Foco automático no primeiro campo
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('email').focus();
  });

  // Suporte a Enter para navegar entre campos
  document.getElementById('email').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      document.getElementById('senha').focus();
    }
  });

  document.getElementById('senha').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      document.getElementById('loginForm').submit();
    }
  });