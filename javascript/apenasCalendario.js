const calendar = document.querySelector(".calendar"),
  date = document.querySelector(".date"),
  daysContainer = document.querySelector(".days"),
  prev = document.querySelector(".prev"),
  next = document.querySelector(".next"),
  todayBtn = document.querySelector(".today-btn"),
  gotoBtn = document.querySelector(".goto-btn"),
  dateInput = document.querySelector(".date-input"),
  eventDay = document.querySelector(".event-day"),
  eventDate = document.querySelector(".event-date"),
  eventsContainer = document.querySelector(".events"),
  addEventBtn = document.querySelector(".add-event"),
  addEventWrapper = document.querySelector(".add-event-wrapper"),
  addEventCloseBtn = document.querySelector(".close"),
  addEventTitle = document.querySelector(".event-name"),
  addEventType = document.querySelector(".event-type"),
  addEventDescription = document.querySelector(".event-description"),
  addEventFrom = document.querySelector(".event-time-from"),
  addEventTo = document.querySelector(".event-time-to"),
  addEventSubmit = document.querySelector(".add-event-btn");

let today = new Date();
let activeDay;
let month = today.getMonth();
let year = today.getFullYear();

const months = [
  "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
  "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro",
];

const eventsArr = [];

// Função para mostrar "Carregando..." no calendário
function showLoading() {
  daysContainer.innerHTML = `<div class="loading">Carregando eventos...</div>`;
}

// Função para carregar eventos do PHP
async function getEvents() {
  try {
    showLoading();
    const response = await fetch('getEventos.php');
    const data = await response.json();

    eventsArr.length = 0;

    data.forEach(evento => {
      let exist = eventsArr.find(ev =>
        ev.day === parseInt(evento.dia) &&
        ev.month === parseInt(evento.mes) &&
        ev.year === parseInt(evento.ano)
      );

      const newEvent = {
        title: evento.nome,
        time: evento.time_from + " - " + evento.time_to,
        type: evento.tipo,
        description: evento.descricao,
        cor_materia: evento.cor_materia
      };

      if (exist) {
        exist.events.push(newEvent);
      } else {
        eventsArr.push({
          day: parseInt(evento.dia),
          month: parseInt(evento.mes),
          year: parseInt(evento.ano),
          events: [newEvent]
        });
      }
    });

    console.log("Eventos carregados do banco:", eventsArr);
    initCalendar();

  } catch (error) {
    console.error('Erro ao buscar eventos:', error);
    daysContainer.innerHTML = `<div class="error">Erro ao carregar eventos.</div>`;
  }
}

getEvents();

// Função para montar o calendário
function initCalendar() {
  const firstDay = new Date(year, month, 1);
  const lastDay = new Date(year, month + 1, 0);
  const prevLastDay = new Date(year, month, 0);
  const prevDays = prevLastDay.getDate();
  const lastDate = lastDay.getDate();
  const day = firstDay.getDay();
  const nextDays = 7 - lastDay.getDay() - 1;

  date.innerHTML = months[month] + " " + year;

  let days = "";

  for (let x = day; x > 0; x--) {
    days += `<div class="day prev-date">${prevDays - x + 1}</div>`;
  }

  for (let i = 1; i <= lastDate; i++) {
    let event = eventsArr.some(eventObj =>
      eventObj.day === i &&
      eventObj.month === month + 1 &&
      eventObj.year === year
    );

    if (
      i === new Date().getDate() &&
      year === new Date().getFullYear() &&
      month === new Date().getMonth()
    ) {
      activeDay = i;
      getActiveDay(i);
      updateEvents(i);
      if (event) {
        days += `<div class="day today active event">${i}</div>`;
      } else {
        days += `<div class="day today active">${i}</div>`;
      }
    } else {
      if (event) {
        days += `<div class="day event">${i}</div>`;
      } else {
        days += `<div class="day">${i}</div>`;
      }
    }
  }

  for (let j = 1; j <= nextDays; j++) {
    days += `<div class="day next-date">${j}</div>`;
  }

  daysContainer.innerHTML = days;
  addListener();
}

function addListener() {
  const days = document.querySelectorAll(".day");
  days.forEach((day) => {
    day.addEventListener("click", (e) => {
      const selectedDate = Number(e.target.innerHTML);
      activeDay = selectedDate;
      getActiveDay(selectedDate);
      updateEvents(selectedDate);

      days.forEach((day) => day.classList.remove("active"));
      e.target.classList.add("active");

      if (e.target.classList.contains("prev-date")) {
        prevMonth();
      } else if (e.target.classList.contains("next-date")) {
        nextMonth();
      }
    });
  });
}

function prevMonth() {
  month--;
  if (month < 0) {
    month = 11;
    year--;
  }
  initCalendar();
}

function nextMonth() {
  month++;
  if (month > 11) {
    month = 0;
    year++;
  }
  initCalendar();
}

function getActiveDay(dateNum) {
  const day = new Date(year, month, dateNum);
  const dayName = day.toString().split(" ")[0];
  eventDay.innerHTML = dayName;
  eventDate.innerHTML = `${dateNum} ${months[month]} ${year}`;
}

function updateEvents(dateNum) {
  let events = "";
  eventsArr.forEach((eventObj) => {
    if (
      dateNum === eventObj.day &&
      month + 1 === eventObj.month &&
      year === eventObj.year
    ) {
      eventObj.events.forEach((event) => {
        // Busca a cor da matéria se existir
        let cor = event.cor_materia || '';
        events += `
          <div class="event">
            <div class="title ${event.type}">
              <i class="fas fa-circle" style="color:${cor}"></i>
              <h3 class="event-title">${event.type}: ${event.title}</h3>
              <span class="event-time">${event.time}</span>
            </div>
            <div class="event-description">
              <p>${event.description}</p>
            </div>
          </div>`;
      });
    }
  });

  eventsContainer.innerHTML = events || `<div class="no-event"><h3>Sem eventos marcados</h3></div>`;
}

todayBtn.addEventListener("click", () => {
  today = new Date();
  month = today.getMonth();
  year = today.getFullYear();
  initCalendar();
});

gotoBtn.addEventListener("click", () => {
  const [inputMonth, inputYear] = dateInput.value.split("/");
  if (inputMonth && inputYear && inputMonth > 0 && inputMonth < 13) {
    month = inputMonth - 1;
    year = parseInt(inputYear);
    initCalendar();
  } else {
    alert("Data inválida");
  }
});

addEventBtn.addEventListener("click", () => addEventWrapper.classList.toggle("active"));
addEventCloseBtn.addEventListener("click", () => addEventWrapper.classList.remove("active"));
