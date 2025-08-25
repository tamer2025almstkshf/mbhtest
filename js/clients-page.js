document.addEventListener('DOMContentLoaded', function () {
    const clientModal = new bootstrap.Modal(document.getElementById('clientModal'));
    const clientModalLabel = document.getElementById('clientModalLabel');
    const clientForm = document.getElementById('clientForm');
    const clientFormContent = document.getElementById('clientFormContent');
    const clientIdField = document.getElementById('client_id');

    // This function fetches the countries list from the server.
    // This avoids embedding PHP in the JavaScript, making it cleaner.
    async function getCountriesOptions() {
        // We can create a simple API endpoint for this later. For now, we'll hardcode a few.
        // In a real scenario, this would be:
        // const response = await fetch('api/getCountries.php');
        // const countries = await response.json();
        // return countries.map(country => `<option value="${country.name}">${country.name}</option>`).join('');
        return `
            <option value="">-- اختر الجنسية --</option>
            <option value="United Arab Emirates">United Arab Emirates</option>
            <option value="Egypt">Egypt</option>
            <option value="Saudi Arabia">Saudi Arabia</option>
            // Add other countries as needed
        `;
    }

    // Function to create form fields HTML
    async function createFormFields(data = {}) {
        const countriesOptions = await getCountriesOptions();
        
        clientFormContent.innerHTML = `
            <h4>المعلومات الأساسية</h4><hr>
            <div class="row">
                <div class="col-md-4 mb-3"><label for="arname" class="form-label">الإسم (عربي) <span class="text-danger">*</span></label><input type="text" id="arname" name="arname" class="form-control" value="${data.arname || ''}" required></div>
                <div class="col-md-4 mb-3"><label for="engname" class="form-label">الإسم (إنجليزي)</label><input type="text" id="engname" name="engname" class="form-control" value="${data.engname || ''}"></div>
                <div class="col-md-4 mb-3"><label for="client_kind" class="form-label">التصنيف <span class="text-danger">*</span></label><select id="client_kind" name="client_kind" class="form-select" required><option value="موكل" ${data.client_kind === 'موكل' ? 'selected' : ''}>موكل</option><option value="خصم" ${data.client_kind === 'خصم' ? 'selected' : ''}>خصم</option><option value="عناوين هامة" ${data.client_kind === 'عناوين هامة' ? 'selected' : ''}>عناوين هامة</option></select></div>
            </div>
            <div class="row">
                 <div class="col-md-6 mb-3"><label for="client_type" class="form-label">الفئة <span class="text-danger">*</span></label><select id="client_type" name="client_type" class="form-select" required><option value="شخص" ${data.client_type === 'شخص' ? 'selected' : ''}>شخص</option><option value="شركة" ${data.client_type === 'شركة' ? 'selected' : ''}>شركة</option><option value="حكومة / مؤسسات" ${data.client_type === 'حكومة / مؤسسات' ? 'selected' : ''}>حكومة / مؤسسات</option></select></div>
                 <div class="col-md-6 mb-3"><label for="country" class="form-label">الجنسية</label><select id="country" name="country" class="form-select">${countriesOptions}</select></div>
            </div>
            <h4 class="mt-4">معلومات الاتصال</h4><hr>
            <div class="row">
                <div class="col-md-6 mb-3"><label for="tel1" class="form-label">هاتف متحرك <span class="text-danger">*</span></label><input type="text" id="tel1" name="tel1" class="form-control" value="${data.tel1 || ''}" required></div>
                <div class="col-md-6 mb-3"><label for="tel2" class="form-label">هاتف آخر</label><input type="text" id="tel2" name="tel2" class="form-control" value="${data.tel2 || ''}"></div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label for="email" class="form-label">البريد الإلكتروني</label><input type="email" id="email" name="email" class="form-control" value="${data.email || ''}"></div>
                <div class="col-md-6 mb-3"><label for="fax" class="form-label">فاكس</label><input type="text" id="fax" name="fax" class="form-control" value="${data.fax || ''}"></div>
            </div>
            <div class="mb-3"><label for="address" class="form-label">العنوان</label><textarea id="address" name="address" class="form-control" rows="2">${data.address || ''}</textarea></div>
        `;
    }

    // Prepare the modal for adding a new client
    window.prepareAddModal = async function() {
        clientForm.reset();
        clientIdField.value = '';
        clientModalLabel.textContent = 'إضافة عميل جديد';
        await createFormFields();
    }

    // Handle the click event for editing a client
    document.querySelector('.table').addEventListener('click', async function (e) {
        const editButton = e.target.closest('.edit-btn');
        if (editButton) {
            const id = editButton.dataset.id;
            clientModalLabel.textContent = 'تعديل بيانات العميل';
            clientIdField.value = id;

            try {
                const response = await fetch(`api/getClient.php?id=${id}`);
                const result = await response.json();

                if (result.status === 'success') {
                    await createFormFields(result.data);
                    document.getElementById('country').value = result.data.country; // Set selected country
                    clientModal.show();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Failed to fetch client data:', error);
                alert('An error occurred while fetching client data.');
            }
        }
    });

    // Handle the save button click
    document.getElementById('saveClientBtn').addEventListener('click', async function() {
        const formData = new FormData(clientForm);
        try {
            const response = await fetch('api/saveClient.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            if (data.status === 'success') {
                clientModal.hide();
                location.reload(); // Simple reload to see changes
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            console.error('Failed to save client data:', error);
            alert('An error occurred while saving client data.');
        }
    });
});
