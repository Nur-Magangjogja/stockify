import 'flowbite';
import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
	const chartCanvas = document.getElementById('transactionChart');
	const categoryCanvas = document.getElementById('categoryChart');
	const transactionContainer = document.getElementById('transactionChartContainer');
	const categoryContainer = document.getElementById('categoryChartContainer');
	
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
				maintainAspectRatio: false,
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

	if (categoryCanvas && typeof Chart !== 'undefined') {
		const catLabels = JSON.parse(categoryCanvas.dataset.labels || '[]');
		const catStock = JSON.parse(categoryCanvas.dataset.stock || '[]');
		const catNames = JSON.parse(categoryCanvas.dataset.categories || '[]');

		new Chart(categoryCanvas.getContext('2d'), {
			type: 'bar',
			data: {
				labels: catLabels,
				datasets: [
					{
						label: 'Total Stok',
						data: catStock,
						backgroundColor: 'rgba(59, 130, 246, 0.8)', // blue-500
						borderColor: '#3B82F6',
						borderWidth: 1,
						borderRadius: 4,
						barThickness: 20,
						maxBarThickness: 30,
					},
				],
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					legend: { display: false },
					tooltip: {
						backgroundColor: '#1F2937',
						borderColor: '#374151',
						borderWidth: 1,
						titleColor: '#F9FAFB',
						bodyColor: '#9CA3AF',
						callbacks: {
							beforeBody: function(context) {
								const index = context[0].dataIndex;
								return 'Kategori: ' + (catNames[index] || '-');
							}
						}
					},
				},
				scales: {
					x: {
						ticks: { color: '#6B7280', font: { size: 10 }, maxRotation: 45, minRotation: 45 },
						grid: { display: false },
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

	// Logic for toggling charts
	const btnTransaction = document.getElementById('btnShowTransaction');
	const btnCategory = document.getElementById('btnShowCategory');
	const legendTransaction = document.getElementById('transactionLegend');
	const titleChart = document.getElementById('mainChartTitle');
	const subtitleChart = document.getElementById('mainChartSubtitle');
	const chartFilter = document.getElementById('transactionChartFilter');

	if (btnTransaction && btnCategory) {
		const setActiveBtn = (active, inactive) => {
			active.classList.replace('text-gray-400', 'text-blue-400');
			active.classList.replace('hover:text-gray-300', 'bg-blue-500/20');
			active.classList.add('bg-blue-500/20');
			
			inactive.classList.replace('text-blue-400', 'text-gray-400');
			inactive.classList.replace('bg-blue-500/20', 'hover:text-gray-300');
			inactive.classList.remove('bg-blue-500/20');
		};

		btnTransaction.addEventListener('click', () => {
			transactionContainer?.classList.remove('hidden');
			categoryContainer?.classList.add('hidden');
			legendTransaction?.classList.remove('hidden');
			chartFilter?.classList.remove('hidden');
			
			titleChart.textContent = 'Grafik Transaksi';
			subtitleChart.textContent = transactionContainer?.dataset.subtitle || new Date().toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
			setActiveBtn(btnTransaction, btnCategory);
		});

		btnCategory.addEventListener('click', () => {
			transactionContainer?.classList.add('hidden');
			categoryContainer?.classList.remove('hidden');
			legendTransaction?.classList.add('hidden');
			chartFilter?.classList.add('hidden');
			
			titleChart.textContent = 'Stok per Produk';
			subtitleChart.textContent = 'Berdasarkan Kategori';
			setActiveBtn(btnCategory, btnTransaction);
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

	// Wheel to scroll logic for horizontal chart container
	if (categoryContainer) {
		categoryContainer.addEventListener('wheel', (e) => {
			// If scrolling vertically, translate it to horizontal scroll
			if (e.deltaY !== 0) {
				e.preventDefault();
				categoryContainer.scrollLeft += e.deltaY;
			}
		});
	}

	document.querySelectorAll('[data-confirm]').forEach((form) => {
		form.addEventListener('submit', (event) => {
			if (!window.confirm(form.dataset.confirm)) event.preventDefault();
		});
	});
	// Settings JS Logic
	const unlockDangerZone = document.getElementById('unlock-danger-zone');
	if (unlockDangerZone) {
		unlockDangerZone.addEventListener('click', function(e) {
			e.preventDefault();
			// Gunakan setTimeout (setTimeout 0) setelah confirm untuk menghindari bug freeze pada elemen <select> native di browser berbasis Chromium
			if (confirm('PERINGATAN: Area ini berisi menu untuk menghapus data sistem secara permanen. Apakah Anda yakin ingin membukanya?')) {
				setTimeout(() => {
					document.getElementById('danger-zone-content').classList.remove('hidden');
					this.classList.add('hidden');
					// Kembalikan fokus ke body agar state browser normal
					document.activeElement.blur();
				}, 10);
			}
		});
	}

	const appLogo = document.getElementById('app_logo');
	if (appLogo) {
		appLogo.addEventListener('change', function(e) {
			const file = e.target.files[0];
			if (file) {
				const reader = new FileReader();
				reader.onload = function(e) {
					const preview = document.getElementById('logo-preview');
					const defaultIcon = document.getElementById('default-logo-icon');
					
					preview.src = e.target.result;
					preview.classList.remove('hidden');
					
					if (defaultIcon) {
						defaultIcon.classList.add('hidden');
					}
				}
				reader.readAsDataURL(file);
			}
		});
	}

	const handleDateOrMonthVisibility = (typeSelectId, dateContainerId, monthContainerId, startDateId, endDateId, monthId) => {
		const typeSelect = document.getElementById(typeSelectId);
		if (typeSelect) {
			typeSelect.addEventListener('change', function(e) {
				const type = e.target.value;
				const dateContainer = document.getElementById(dateContainerId);
				const monthContainer = document.getElementById(monthContainerId);
				const startDate = document.getElementById(startDateId);
				const endDate = document.getElementById(endDateId);
				const specificMonth = document.getElementById(monthId);

				if (type === 'date_range') {
					dateContainer.classList.remove('hidden');
					monthContainer.classList.add('hidden');
					startDate.required = true;
					endDate.required = true;
					specificMonth.required = false;
				} else {
					dateContainer.classList.add('hidden');
					monthContainer.classList.remove('hidden');
					startDate.required = false;
					endDate.required = false;
					specificMonth.required = true;
				}
			});
		}
	};

	handleDateOrMonthVisibility('delete_type', 'container_date', 'container_month', 'start_date', 'end_date', 'specific_month');
	handleDateOrMonthVisibility('activity_delete_type', 'activity_container_date', 'activity_container_month', 'activity_start_date', 'activity_end_date', 'activity_specific_month');

	// Product selection logic for create-in and create-out
	const productSelect = document.getElementById('product-select');
	const productInfo = document.getElementById('product-info');
	const currentStock = document.getElementById('current-stock');
	const unitPriceHidden = document.getElementById('unit-price-hidden');
	const unitPriceDisplay = document.getElementById('unit-price-display');

	if (productSelect) {
		function updateProductDetails() {
			const selectedOption = productSelect.options[productSelect.selectedIndex];
			
			if (selectedOption.value) {
				const stock = selectedOption.getAttribute('data-stock');
				const unit = selectedOption.getAttribute('data-unit');
				const price = parseInt(selectedOption.getAttribute('data-price')) || 0;
				const supplierId = selectedOption.getAttribute('data-supplier');
				const sku = selectedOption.getAttribute('data-sku');

				if (currentStock) currentStock.textContent = `${stock} ${unit}`;
				if (productInfo) productInfo.classList.remove('hidden');
				
				const quantityInput = document.getElementById('quantity-input');
				if (quantityInput && !document.getElementById('supplier-select')) { // if it's create-out, it has no supplier-select usually, or just limit anyway
					quantityInput.max = stock;
				}

				if (unitPriceHidden) unitPriceHidden.value = price;
				if (unitPriceDisplay) unitPriceDisplay.value = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(price);
				
				const supplierSelect = document.getElementById('supplier-select');
				if(supplierSelect && supplierId) {
					supplierSelect.value = supplierId;
				}
				const refInput = document.getElementById('reference-input');
				if(refInput && sku) {
					refInput.value = sku;
				}
			} else {
				if (productInfo) productInfo.classList.add('hidden');
				const quantityInput = document.getElementById('quantity-input');
				if (quantityInput) quantityInput.removeAttribute('max');
				if (unitPriceHidden) unitPriceHidden.value = '';
				if (unitPriceDisplay) unitPriceDisplay.value = '';
				const supplierSelect = document.getElementById('supplier-select');
				if(supplierSelect) supplierSelect.value = '';
				const refInput = document.getElementById('reference-input');
				if(refInput) refInput.value = '';
			}
		}

		productSelect.addEventListener('change', updateProductDetails);
		
		if (productSelect.value) {
			updateProductDetails();
		}
	}
});
