import 'flowbite';
import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
	const chartCanvas = document.getElementById('transactionChart');
	if (chartCanvas && typeof Chart !== 'undefined') {
		const labels = JSON.parse(chartCanvas.dataset.labels || '[]');
		const dataMasuk = JSON.parse(chartCanvas.dataset.masuk || '[]');
		const dataKeluar = JSON.parse(chartCanvas.dataset.keluar || '[]');

		new Chart(chartCanvas.getContext('2d'), {
			type: 'line',
			data: {
				labels: labels,
				datasets: [
					{
						label: 'Masuk',
						data: dataMasuk,
						borderColor: '#3B82F6',
						backgroundColor: 'rgba(59, 130, 246, 0.1)',
						tension: 0.4,
						fill: true,
						pointRadius: 3,
						pointHoverRadius: 5,
					},
					{
						label: 'Keluar',
						data: dataKeluar,
						borderColor: '#EF4444',
						backgroundColor: 'rgba(239, 68, 68, 0.1)',
						tension: 0.4,
						fill: true,
						pointRadius: 3,
						pointHoverRadius: 5,
					},
				],
			},
			options: {
				responsive: true,
				plugins: {
					legend: { display: false },
					tooltip: {
						backgroundColor: '#1F2937',
						borderColor: '#374151',
						borderWidth: 1,
						titleColor: '#F9FAFB',
						bodyColor: '#9CA3AF',
					},
				},
				scales: {
					x: {
						ticks: { color: '#6B7280', font: { size: 10 } },
						grid: { color: 'rgba(75, 85, 99, 0.3)' },
					},
					y: {
						ticks: { color: '#6B7280', font: { size: 10 } },
						grid: { color: 'rgba(75, 85, 99, 0.3)' },
						beginAtZero: true,
					},
				},
			},
		});
	}

	const attributeContainer = document.getElementById('attributes-container');
	const addAttributeButton = document.getElementById('add-attribute');
	if (attributeContainer && addAttributeButton) {
		let attributeIndex = Number(attributeContainer.dataset.index || 0);
		const noAttributeText = document.getElementById('no-attr-text');
		addAttributeButton.addEventListener('click', () => {
			noAttributeText?.classList.add('hidden');
			const row = document.createElement('div');
			row.className = 'attribute-row flex flex-col sm:flex-row gap-2 sm:items-center bg-gray-900/30 sm:bg-transparent p-3 sm:p-0 rounded-lg border border-gray-700 sm:border-transparent mb-2 sm:mb-0';
			row.innerHTML = `
				<input type="text" name="attributes[${attributeIndex}][attribute_name]" placeholder="Nama Atribut" required
					class="w-full sm:flex-1 bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
				<input type="text" name="attributes[${attributeIndex}][attribute_value]" placeholder="Nilai Atribut" required
					class="w-full sm:flex-1 bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
				<button type="button" data-remove-attribute class="w-full sm:w-auto p-2 text-red-400 hover:bg-red-900/20 rounded-lg transition-all flex justify-center items-center gap-1 border border-red-500/20 sm:border-transparent bg-red-500/10 sm:bg-transparent flex-shrink-0 mt-1 sm:mt-0">
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
					<span class="text-xs font-medium sm:hidden">Hapus</span>
				</button>`;
			attributeContainer.appendChild(row);
			attributeIndex += 1;
		});
		attributeContainer.addEventListener('click', (event) => {
			if (event.target.closest('[data-remove-attribute]')) {
				event.target.closest('.attribute-row')?.remove();
				if (!attributeContainer.children.length) noAttributeText?.classList.remove('hidden');
			}
		});
	}

	const imageInput = document.getElementById('image');
	const imageUploadArea = document.getElementById('image-upload-area') || imageInput?.parentElement;
	imageUploadArea?.addEventListener('click', (event) => {
		if (event.target !== imageInput) imageInput?.click();
	});
	imageInput?.addEventListener('change', () => {
		const file = imageInput.files?.[0];
		if (!file) return;
		const reader = new FileReader();
		reader.addEventListener('load', (event) => {
			const preview = document.getElementById('image-preview');
			preview.src = event.target.result;
			preview.classList.remove('hidden');
			document.getElementById('upload-placeholder')?.classList.add('hidden');
		});
		reader.readAsDataURL(file);
	});


	document.querySelectorAll('[data-system]').forEach((input) => {
		const updateDifference = () => {
			const difference = Number(input.value || 0) - Number(input.dataset.system || 0);
			const target = input.closest('tr')?.querySelector('[data-difference]')
				|| document.getElementById(`diff-${input.name.match(/\[(\d+)\]/)?.[1]}`);
			if (!target) return;
			const colorClass = difference > 0 ? 'text-green-400' : difference < 0 ? 'text-red-400' : 'text-gray-400';
			if (target.id.startsWith('diff-')) {
				target.innerHTML = `<span class="font-semibold ${colorClass}">${difference > 0 ? '+' : ''}${difference}</span>`;
			} else {
				target.textContent = difference > 0 ? `+${difference}` : difference;
				target.className = `px-4 py-3 text-center ${colorClass}`;
			}
		};
		input.addEventListener('input', updateDifference);
		updateDifference();
	});

	const passwordToggle = document.getElementById('toggle-password');
	const passwordInput = document.getElementById('password');
	passwordToggle?.addEventListener('click', () => {
		passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
	});

	document.querySelectorAll('[data-confirm]').forEach((form) => {
		form.addEventListener('submit', (event) => {
			if (!window.confirm(form.dataset.confirm)) event.preventDefault();
		});
	});
});
