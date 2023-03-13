import './App.css';
import './components/event.css';
import './components/task.css';
import React, { useMemo, useState, useEffect } from 'react';
import TaskBox from './components/TaskBox';
// npm run dev to run this app
function App() {
  const initEvent = useMemo(() => [
    {
      title: 'My Tasks',
      ['To do']: [{
        "id": 0,
        "name": "Todo",
        "description": "This is for test",
        "status": "todo"
      }],
      ['In progress']: [{
        "id": 1,
        "name": "In Progress",
        "description": "This is for test",
        "status": "inprogress"
      }],
      ['Done']: [{
        "id": 2,
        "name": "Done",
        "description": "This is for test",
        "status": "done"
      }],
    },
  ], []);

  const [events, setEvents] = useState(() => initEvent);
  const [loading, setLoading] = useState(false);
  const [currentEvent, setCurrentEvent] = useState(events[0]);

  useEffect(() => {
    getTasks();
  }, []);

  // get tasks
  const getTasks = async () => {
    const token = 'f6eb96dfd6a9b91ea8b631325b175c108ef3783c134e3122d6ca23eb5ad5662b';
    const headers = { 'Authorization': `Bearer ${token}` };
    setLoading(true);
    await fetch(
      "http://localhost:8000/api/task", { headers })
      .then((res) => res.json())
      .then((json) => {
        let task = json.data.task ?? [];
        let events = [
          {
            title: 'My Tasks',
            ['To do']: task.todo ?? [],
            ['In progress']: task.inprogress ?? [],
            ['Done']: task.done ?? [],
          },
        ];

        setEvents(events);
      }).catch(err => console.error(err));
    setLoading(false);
  }

  return (
    <div className='App'>
      <div className='event-bar'>
        <h1 className='event-bar-title'>Kanban Board</h1>
        <div className='event-container'>
          <div className='event over-hide selected-event'>
            My Task
          </div>
        </div>
      </div>
      <TaskBox
        events={events}
        isLoading={loading}
        setEvents={setEvents}
        currentEvent={currentEvent}
        setCurrentEvent={setCurrentEvent}
      />
    </div>
  );
}

export default App;
