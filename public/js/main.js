// JS principal
console.log('E-commerce scaffold loaded');

document.addEventListener('DOMContentLoaded', function() {
	const btnOpen = document.getElementById('btnOpenProductModal');
	const modalBg = document.getElementById('productModalBg');
	const btnCancel = document.getElementById('btnCancelProduct');
	const form = document.getElementById('productForm');
	const errorDiv = document.getElementById('productFormError');
	if (btnOpen && modalBg) {
		btnOpen.addEventListener('click', () => { modalBg.style.display = 'flex'; });
	}
	if (btnCancel && modalBg) {
		btnCancel.addEventListener('click', () => { modalBg.style.display = 'none'; });
	}
	if (modalBg) {
		modalBg.addEventListener('click', (e) => { if (e.target === modalBg) modalBg.style.display = 'none'; });
	}
	if (form) {
		form.addEventListener('submit', async function(e) {
			e.preventDefault();
			errorDiv.style.display = 'none';
			const formData = new FormData(form);
			const editId = form.getAttribute('data-edit-id');
			const url = editId ? `?action=update_product&id=${editId}` : '?action=store_product';
			try {
				const res = await fetch(url, {
					method: 'POST',
					body: formData,
					headers: { 'X-Requested-With': 'XMLHttpRequest' }
				});
				const data = await res.json();
				if (data.success) {
					modalBg.style.display = 'none';
					window.location.reload();
				} else {
					errorDiv.textContent = data.error || 'Error al guardar producto';
					errorDiv.style.display = 'block';
				}
			} catch (err) {
				errorDiv.textContent = 'Error de red o servidor';
				errorDiv.style.display = 'block';
			}
		});
	}
});