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

  // Verificação de força da senha
  function checkPasswordStrength(password) {
    const strengthDiv = document.getElementById('passwordStrength');
    let strength = 0;
    let message = '';
    let className = '';

    if (password.length >= 6) strength++;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;

    switch (strength) {
      case 0:
      case 1:
        message = 'Senha muito fraca';
        className = 'strength-weak';
        break;
      case 2:
        message = 'Senha fraca';
        className = 'strength-weak';
        break;
      case 3:
        message = 'Senha média';
        className = 'strength-medium';
        break;
      case 4:
        message = 'Senha boa';
        className = 'strength-strong';
        break;
      case 5:
        message = 'Senha muito forte';
        className = 'strength-strong';
        break;
    }

    strengthDiv.textContent = message;
    strengthDiv.className = 'password-strength ' + className;
  }

  // Validação do formulário
  document.getElementById('cadastroForm').addEventListener('submit', function(e) {
    const nome = document.getElementById('nome').value.trim();
    const email = document.getElementById('email').value.trim();
    const senha = document.getElementById('senha').value;
    const confirmarSenha = document.getElementById('confirmarSenha').value;
    
    if (!nome || !email || !senha || !confirmarSenha) {
      e.preventDefault();
      alert('Por favor, preencha todos os campos.');
      return false;
    }
    
    // Validação de email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      e.preventDefault();
      alert('Por favor, insira um email válido.');
      return false;
    }
    
    // Validação de senha
    if (senha.length < 6) {
      e.preventDefault();
      alert('A senha deve ter pelo menos 6 caracteres.');
      return false;
    }
    
    // Validação de confirmação de senha
    if (senha !== confirmarSenha) {
      e.preventDefault();
      alert('As senhas não coincidem.');
      return false;
    }
  });

  // Event listener para verificar força da senha
  document.getElementById('senha').addEventListener('input', function() {
    checkPasswordStrength(this.value);
  });

  // Foco automático no primeiro campo
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('nome').focus();
  });

  // Suporte a Enter para navegar entre campos
  document.getElementById('nome').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      document.getElementById('email').focus();
    }
  });

  document.getElementById('email').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      document.getElementById('senha').focus();
    }
  });

  document.getElementById('senha').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      document.getElementById('confirmarSenha').focus();
    }
  });

  document.getElementById('confirmarSenha').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      document.getElementById('cadastroForm').submit();
    }
  });