  // Funções para ações
  function editarDespesa(id) {
    window.location.href = 'editar_despesa.php?id=' + id;
  }

  
  function deletarDespesa(id) {
    if (confirm('Tem certeza que deseja excluir esta despesa?')) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = 'DeletarDespesa.php';
      
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'id';
      input.value = id;
      
      form.appendChild(input);
      document.body.appendChild(form);
      form.submit();
    }
  }
  