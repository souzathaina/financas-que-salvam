// Validação do formulário
document.getElementById('editarForm').addEventListener('submit', function(e) {
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