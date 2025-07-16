// Validação do formulário
document.getElementById('categoriaForm').addEventListener('submit', function(e) {
    const nome = document.getElementById('nome').value.trim();
    
    if (!nome) {
      e.preventDefault();
      alert('Por favor, insira o nome da categoria.');
      return false;
    }
    
    if (nome.length < 3) {
      e.preventDefault();
      alert('O nome da categoria deve ter pelo menos 3 caracteres.');
      return false;
    }
    
    if (nome.length > 50) {
      e.preventDefault();
      alert('O nome da categoria deve ter no máximo 50 caracteres.');
      return false;
    }
  });

  // Foco automático no campo
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('nome').focus();
  });
   // Validação do formulário
   document.getElementById('despesaForm').addEventListener('submit', function(e) {
    const categoria = document.getElementById('categoria').value;
    const valor = document.getElementById('valor').value;
    const data = document.getElementById('data').value;
    const descricao = document.getElementById('descricao').value.trim();
    
    if (!categoria || !valor || !data || !descricao) {
      e.preventDefault();
      alert('Por favor, preencha todos os campos obrigatórios.');
      return false;
    }
    
    if (parseFloat(valor) <= 0) {
      e.preventDefault();
      alert('Por favor, insira um valor válido maior que zero.');
      return false;
    }
    
    if (descricao.length < 3) {
      e.preventDefault();
      alert('A descrição deve ter pelo menos 3 caracteres.');
      return false;
    }
  });

  // Formatação automática do valor
  document.getElementById('valor').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^\d,]/g, '');
    value = value.replace(',', '.');
    if (value.includes('.')) {
      const parts = value.split('.');
      if (parts[1].length > 2) {
        parts[1] = parts[1].substring(0, 2);
      }
      value = parts[0] + '.' + parts[1];
    }
    e.target.value = value;
  });

  // Foco automático no primeiro campo
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('categoria').focus();
  });