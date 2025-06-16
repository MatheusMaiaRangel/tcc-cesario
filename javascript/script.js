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

  //recebe evento
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

//meses do ano
const months = [
  "Janeiro",
  "Fevereiro",
  "Março",
  "Abril",
  "Maio",
  "Junho",
  "Julho",
  "Agosto",
  "Setembro",
  "Outubro",
  "November",
  "Dezembro",
];

const eventsArr = [];
getEvents().then(() => {
  initCalendar(); // Inicializa o calendário após carregar os eventos
  const todayDate = today.getDate();
  activeDay = todayDate;
  getActiveDay(todayDate);
  updateEvents(todayDate); // Atualiza os eventos após carregar os dados
});

//function to add days in days with class day and prev-date next-date on previous month and next month days and active on today
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
    //check if event is present on that day
    let event = false;
    eventsArr.forEach((eventObj) => {
      if (
        eventObj.day === i &&
        eventObj.month === month + 1 &&
        eventObj.year === year
      ) {
        event = true;
      }
    });
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
        days += `<div class="day ">${i}</div>`;
      }
    }
  }

  for (let j = 1; j <= nextDays; j++) {
    days += `<div class="day next-date">${j}</div>`;
  }
  daysContainer.innerHTML = days;
  addListner();
}

//function to add month and year on prev and next button
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

prev.addEventListener("click", prevMonth);
next.addEventListener("click", nextMonth);

//function to add active on day
function addListner() {
  const days = document.querySelectorAll(".day");
  days.forEach((day) => {
    day.addEventListener("click", (e) => {
      getActiveDay(e.target.innerHTML);
      updateEvents(Number(e.target.innerHTML));
      activeDay = Number(e.target.innerHTML);
      //remove active
      days.forEach((day) => {
        day.classList.remove("active");
      });
      //if clicked prev-date or next-date switch to that month
      if (e.target.classList.contains("prev-date")) {
        prevMonth();
        //add active to clicked day afte month is change
        setTimeout(() => {
          //add active where no prev-date or next-date
          const days = document.querySelectorAll(".day");
          days.forEach((day) => {
            if (
              !day.classList.contains("prev-date") &&
              day.innerHTML === e.target.innerHTML
            ) {
              day.classList.add("active");
            }
          });
        }, 100);
      } else if (e.target.classList.contains("next-date")) {
        nextMonth();
        //add active to clicked day afte month is changed
        setTimeout(() => {
          const days = document.querySelectorAll(".day");
          days.forEach((day) => {
            if (
              !day.classList.contains("next-date") &&
              day.innerHTML === e.target.innerHTML
            ) {
              day.classList.add("active");
            }
          });
        }, 100);
      } else {
        e.target.classList.add("active");
      }
    });
  });
}

todayBtn.addEventListener("click", () => {
  today = new Date();
  month = today.getMonth();
  year = today.getFullYear();
  initCalendar();
});

dateInput.addEventListener("input", (e) => {
  dateInput.value = dateInput.value.replace(/[^0-9/]/g, "");
  if (dateInput.value.length === 2) {
    dateInput.value += "/";
  }
  if (dateInput.value.length > 7) {
    dateInput.value = dateInput.value.slice(0, 7);
  }
  if (e.inputType === "deleteContentBackward") {
    if (dateInput.value.length === 3) {
      dateInput.value = dateInput.value.slice(0, 2);
    }
  }
});

gotoBtn.addEventListener("click", gotoDate);

//função para ir até o dia
function gotoDate() {
  const dateArr = dateInput.value.split("/");
  if (dateArr.length === 2) {
    if (dateArr[0] > 0 && dateArr[0] < 13 && dateArr[1].length === 4) {
      month = dateArr[0] - 1;
      year = dateArr[1];
      initCalendar();
      return;
    }
  }
  alert("Data inválida");
}

//function get active day day name and date and update eventday eventdate
function getActiveDay(date) {
  const day = new Date(year, month, date);
  const dayName = day.toString().split(" ")[0];
  eventDay.innerHTML = dayName;
  eventDate.innerHTML = date + " " + months[month] + " " + year;
}

//function update events when a day is active
function updateEvents(date) {
  let events = "";
  eventsArr.forEach((event) => {
    if (
      date === event.day &&
      month + 1 === event.month &&
      year === event.year
    ) {
      event.events.forEach((event) => {
        // Busca a cor da matéria do evento
        let cor = event.cor_materia || '';
        let turmaInfo = event.turma_nome ? ` ${event.turma_nome}` : '';
        events += `<div class="event" style="margin-bottom: 20px;">
            <div class="title">
              <i class="fas fa-circle" style="color:${cor}"></i>
              <h3 class="event-title" style="color:${cor}">${event.type}: ${turmaInfo}</h3><span class="event-time">${event.time}</span>
            </div>
            <div class="event-time">
              <p> ${event.title}: ${event.description}</p>
            </div>
        </div>`;
      });
    }
  });
  if (events === "") {
    events = `<div class="no-event">
            <h3>Sem eventos marcados</h3>
        </div>`;
  }
  eventsContainer.innerHTML = events;
}

//allow 60 chars in eventtitle
addEventTitle.addEventListener("input", (e) => {
  addEventTitle.value = addEventTitle.value.slice(0, 60);
});

//allow only time in eventtime from and to
addEventFrom.addEventListener("input", (e) => {
  addEventFrom.value = addEventFrom.value.replace(/[^0-9:]/g, "");
  if (addEventFrom.value.length === 2) {
    addEventFrom.value += ":";
  }
  if (addEventFrom.value.length > 5) {
    addEventFrom.value = addEventFrom.value.slice(0, 5);
  }
});

addEventTo.addEventListener("input", (e) => {
  addEventTo.value = addEventTo.value.replace(/[^0-9:]/g, "");
  if (addEventTo.value.length === 2) {
    addEventTo.value += ":";
  }
  if (addEventTo.value.length > 5) {
    addEventTo.value = addEventTo.value.slice(0, 5);
  }
});

// =======================
// ENVIO DE EVENTO (APENAS UM BLOCO!)
// =======================
document.getElementById('add-event-form').addEventListener('submit', async function(e) {
  e.preventDefault();
  document.getElementById("event-day-input").value = activeDay;
  document.getElementById("event-month-input").value = month + 1;
  document.getElementById("event-year-input").value = year;

  const eventTitle = addEventTitle.value.trim();
  const eventTimeFrom = addEventFrom.value.trim();
  const eventTimeTo = addEventTo.value.trim();
  const eventType = addEventType.value.trim();
  const eventDesc = addEventDescription.value.trim();
  const eventTurma = document.querySelector('.event-turma').value;
  const eventPredio = document.querySelector('.event-predio').value;

  if (!eventTitle || !eventTimeFrom || !eventTimeTo || !eventType || !eventDesc || !eventTurma) {
    alert("Preencha todos os campos obrigatórios.");
    return;
  }

  // Se for para todas as turmas do prédio
  if (eventTurma === 'ALL_BY_BUILDING' && eventPredio) {
    // Pega todas as turmas do prédio selecionado
    const turmasDoPredio = turmasCache.filter(opt => opt.getAttribute('data-predio') === eventPredio);
    let sucesso = 0, falha = 0;
    for (const turmaOpt of turmasDoPredio) {
      const turmaId = turmaOpt.value;
      const newEvent = {
        event_nome: eventTitle,
        event_time_from: eventTimeFrom,
        event_time_to: eventTimeTo,
        event_description: eventDesc,
        event_type: eventType,
        event_day: activeDay,
        event_month: month + 1,
        event_year: year,
        event_turma: turmaId
      };
      try {
        const resp = await fetch("addEvento.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(newEvent)
        });
        const data = await resp.json();
        if (data.status === "success") sucesso++;
        else falha++;
      } catch {
        falha++;
      }
    }
    alert(`Eventos enviados: ${sucesso}, falhas: ${falha}`);
    if (sucesso) {
      getEvents();
      addEventTitle.value = "";
      addEventFrom.value = "";
      addEventTo.value = "";
      addEventType.value = "";
      addEventDescription.value = "";
      turmaSelect.value = "";
      predioSelect.value = "";
    }
    return;
  }

  // Caso padrão (apenas uma turma)
  const newEvent = {
    event_nome: eventTitle,
    event_time_from: eventTimeFrom,
    event_time_to: eventTimeTo,
    event_description: eventDesc,
    event_type: eventType,
    event_day: activeDay,
    event_month: month + 1,
    event_year: year,
    event_turma: eventTurma
  };

  fetch("addEvento.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(newEvent),
  })
    .then((response) => response.json())
    .then((data) => {
      alert(data.message);
      if (data.status === "success") {
        getEvents(); // Recarrega os eventos após salvar
        addEventTitle.value = "";
        addEventFrom.value = "";
        addEventTo.value = "";
        addEventType.value = "";
        addEventDescription.value = "";
        turmaSelect.value = "";
        predioSelect.value = "";
      }
    })
    .catch((error) => {
      alert("Erro ao salvar evento.");
      console.error(error);
    });
});

//recebe as variaveis do getEventos.php
async function getEvents() {
  try {
    const response = await fetch('getEventos.php');
    const data = await response.json();

    eventsArr.length = 0; // Limpa antes de popular (importante)

    data.forEach(evento => {
      let exist = eventsArr.find(ev =>
        ev.day === parseInt(evento.dia) &&
        ev.month === parseInt(evento.mes) &&
        ev.year === parseInt(evento.ano)
      );

      const newEvent = {
        id: evento.id,
        title: evento.nome,
        time: evento.time_from + " - " + evento.time_to,
        type: evento.tipo,
        description: evento.descricao,
        cor_materia: evento.cor_materia || '',
        turma_nome: evento.Nome_Turma || '',
        turma_id: evento.fk_Turma_Id_Turma || ''
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

    initCalendar();

  } catch (error) {
    console.error('Erro ao buscar eventos:', error);
  }
}
//fução de horario
function convertTime(time) {
  let timeArr = time.split(":");
  let timeHour = timeArr[0];
  let timeMin = timeArr[1];
  let timeFormat = timeHour >= 12 ? "PM" : "AM";
  timeHour = timeHour % 12 || 12;
  time = timeHour + ":" + timeMin + " " + timeFormat;
  return time;
}

// --- FILTRO DE TURMAS POR PRÉDIO E ENVIO PARA TODAS AS TURMAS DO PRÉDIO ---
const turmaSelect = document.querySelector('.event-turma');
const predioSelect = document.querySelector('.event-predio');
// O cache deve ser feito logo no carregamento, com todas as opções originais
let turmasCache = [];
if (turmaSelect) {
  turmasCache = Array.from(document.querySelectorAll('.event-turma option'))
    .filter(opt => opt.value && opt.value !== 'ALL_BY_BUILDING' && opt.getAttribute('data-predio'));
}

if (predioSelect && turmaSelect) {
  predioSelect.addEventListener('change', function() {
    const predioSelecionado = this.value;
    turmaSelect.innerHTML = '<option value="">Selecione a turma</option>';
    // Salva o valor atual da matéria
    const eventTypeSelect = document.querySelector('.event-type');
    const selectedEventType = eventTypeSelect ? eventTypeSelect.value : null;
    // Adiciona a opção de todas as turmas do prédio SEMPRE que um prédio for selecionado
    if (predioSelecionado) {
      const optAll = document.createElement("option");
      optAll.value = "ALL_BY_BUILDING";
      optAll.textContent = "Todas as turmas do prédio selecionado";
      optAll.style.fontWeight = 'bold';
      turmaSelect.appendChild(optAll);
    }
    turmasCache.forEach(opt => {
      if (!predioSelecionado || opt.getAttribute('data-predio') === predioSelecionado) {
        turmaSelect.appendChild(opt.cloneNode(true));
      }
    });
    // Restaura o valor da matéria
    if (eventTypeSelect && selectedEventType) {
      eventTypeSelect.value = selectedEventType;
    }
  });
}
