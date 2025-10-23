document.addEventListener('DOMContentLoaded', function() {
    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();
    
    const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                       "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    
    const dayNames = ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"];
    
    function renderCalendar() {
        // Obtener el primer día del mes y el último día del mes
        const firstDay = new Date(currentYear, currentMonth, 1);
        const lastDay = new Date(currentYear, currentMonth + 1, 0);
        
        // Actualizar el título del mes
        document.getElementById('current-month').textContent = 
            `${monthNames[currentMonth]} ${currentYear}`;
        
        // Limpiar el calendario
        const calendarGrid = document.getElementById('calendar');
        calendarGrid.innerHTML = '';
        
        // Añadir encabezados de días
        for (let i = 0; i < 7; i++) {
            const dayHeader = document.createElement('div');
            dayHeader.className = 'day-header';
            dayHeader.textContent = dayNames[i];
            calendarGrid.appendChild(dayHeader);
        }
        
        // Añadir días vacíos si el mes no comienza en domingo
        for (let i = 0; i < firstDay.getDay(); i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'day-cell empty';
            calendarGrid.appendChild(emptyDay);
        }
        
        // Añadir los días del mes
        for (let i = 1; i <= lastDay.getDate(); i++) {
            const dayCell = document.createElement('div');
            dayCell.className = 'day-cell';
            
            const dayNumber = document.createElement('div');
            dayNumber.className = 'day-number';
            dayNumber.textContent = i;
            dayCell.appendChild(dayNumber);
            
            // Verificar si es hoy
            const today = new Date();
            if (i === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear()) {
                dayCell.classList.add('today');
            }
            
            // Verificar si hay citas este día (aquí puedes integrar con tu sistema)
            // dayCell.classList.add('has-appointment');
            
            calendarGrid.appendChild(dayCell);
        }
    }
    
    // Botones de navegación
    document.getElementById('prev-month').addEventListener('click', function() {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar();
    });
    
    document.getElementById('next-month').addEventListener('click', function() {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar();
    });
    
    // Inicializar el calendario
    renderCalendar();
});