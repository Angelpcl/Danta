document.addEventListener('DOMContentLoaded', function() {
    const appointmentForm = document.getElementById('appointment-form');
    const appointmentsContainer = document.getElementById('appointments-container');
    
    // Cargar citas almacenadas
    let appointments = JSON.parse(localStorage.getItem('danta11-appointments')) || [];
    
    function renderAppointments() {
        appointmentsContainer.innerHTML = '';
        
        if (appointments.length === 0) {
            appointmentsContainer.innerHTML = '<p>No hay citas programadas.</p>';
            return;
        }
        
        // Ordenar citas por fecha
        appointments.sort((a, b) => new Date(a.date) - new Date(b.date));
        
        appointments.forEach((appointment, index) => {
            const appointmentItem = document.createElement('div');
            appointmentItem.className = 'appointment-item';
            
            const date = new Date(appointment.date);
            const formattedDate = date.toLocaleDateString('es-ES', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            appointmentItem.innerHTML = `
                <h3>${appointment.patientName}</h3>
                <p><strong>Fecha:</strong> ${formattedDate} a las ${appointment.time}</p>
                <p><strong>Tratamiento:</strong> ${getTreatmentName(appointment.treatment)}</p>
                <p><strong>Contacto:</strong> ${appointment.phone} ${appointment.email ? `(${appointment.email})` : ''}</p>
                <button class="btn-cancel" data-index="${index}">Cancelar Cita</button>
            `;
            
            appointmentsContainer.appendChild(appointmentItem);
        });
        
        // Añadir event listeners a los botones de cancelar
        document.querySelectorAll('.btn-cancel').forEach(button => {
            button.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-index'));
                cancelAppointment(index);
            });
        });
    }
    
    function getTreatmentName(treatmentKey) {
        const treatments = {
            'brackets': 'Brackets',
            'alineadores': 'Alineadores Invisibles',
            'implantes': 'Implantes Dentales',
            'limpieza': 'Limpieza Dental',
            'consulta': 'Consulta'
        };
        return treatments[treatmentKey] || treatmentKey;
    }
    
    function cancelAppointment(index) {
        if (confirm('¿Está seguro que desea cancelar esta cita?')) {
            appointments.splice(index, 1);
            localStorage.setItem('danta11-appointments', JSON.stringify(appointments));
            renderAppointments();
        }
    }
    
    appointmentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const newAppointment = {
            patientName: document.getElementById('patient-name').value,
            date: document.getElementById('appointment-date').value,
            time: document.getElementById('appointment-time').value,
            treatment: document.getElementById('treatment').value,
            phone: document.getElementById('phone').value,
            email: document.getElementById('email').value,
            sendReminder: document.getElementById('send-reminder').checked,
            createdAt: new Date().toISOString()
        };
        
        // Validar fecha futura
        const appointmentDate = new Date(newAppointment.date);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (appointmentDate < today) {
            alert('Por favor seleccione una fecha futura');
            return;
        }
        
        appointments.push(newAppointment);
        localStorage.setItem('danta11-appointments', JSON.stringify(appointments));
        
        // Enviar recordatorio si está marcado
        if (newAppointment.sendReminder && newAppointment.email) {
            sendReminder(newAppointment);
        }
        
        // Resetear formulario
        appointmentForm.reset();
        
        // Actualizar lista
        renderAppointments();
        
        alert('Cita agendada exitosamente!');
    });
    
    function sendReminder(appointment) {
        // En un sistema real, aquí enviarías un email
        console.log('Recordatorio enviado a:', appointment.email);
        console.log('Detalles de la cita:', appointment);
        
        // Esto es solo para simulación
        alert(`Se enviará un recordatorio a ${appointment.email} un día antes de la cita.`);
    }
    
    // Inicializar la lista de citas
    renderAppointments();
    
    // Configurar fecha mínima en el input de fecha (hoy)
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('appointment-date').min = today;
});