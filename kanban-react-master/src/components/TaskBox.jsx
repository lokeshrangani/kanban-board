import React, { useCallback, useState } from 'react';
import Column from './Column';
import { DragDropContext } from 'react-beautiful-dnd';
import Loading from './Loading';

const TaskBox = ({ events, setEvents, currentEvent, isLoading }) => {
  const [loading, setLoading] = useState(false);

  const handleDragEnd = useCallback((result) => {
    if (!result.destination) return;
    const { source, destination } = result;
    const curEvent = events.find((item) => item.title === currentEvent.title);
    const taskCopy = curEvent[source.droppableId][source.index];

    let status = {
      'To do': 'todo',
      'In progress': 'inprogress',
      'Done': 'done'
    };

    updateStatus(taskCopy.id, status[destination.droppableId]);

    setEvents((prev) =>
      prev.map((event) => {
        if (event.title === currentEvent.title) {
          let eventCopy = { ...event };
          // Remove from source
          const taskListSource = event[source.droppableId];
          taskListSource.splice(source.index, 1);
          eventCopy = { ...event, [source.droppableId]: taskListSource };
          // Add to destination
          const taskListDes = event[destination.droppableId];
          taskListDes.splice(destination.index, 0, taskCopy);
          eventCopy = { ...event, [destination.droppableId]: taskListDes };
          return eventCopy;
        } else {
          return event;
        }
      })
    );
  }, [events, setEvents, currentEvent]);

  // update status
  const updateStatus = async (task_id, status) => {
    let param = {
      'task_id': task_id, // task
      'status': status, // status
    }

    const token = 'f6eb96dfd6a9b91ea8b631325b175c108ef3783c134e3122d6ca23eb5ad5662b';
    const headers = { 'Authorization': `Bearer ${token}`, "Content-type": "application/json; charset=UTF-8" };

    setLoading(true);
    await fetch('http://localhost:8000/api/task/update', {
      method: "POST",
      body: JSON.stringify(param),
      headers: headers
    })
      .then(response => response.json())
      .then(json => console.log(json))
      .catch(err => console.error(err));

    setLoading(false);
  }

  return (
    <div className='task-box'>
      <header className='task-box-header'>
        <h1 className='task-box-title'>All Tasks {(loading || isLoading) && <Loading />}</h1>
      </header>
      <DragDropContext onDragEnd={(result) => handleDragEnd(result)}>
        <div className='task-box-body'>
          {
            ['To do', 'In progress', 'Done'].map(tag => (
              <Column
                key={tag}
                tag={tag}
                events={events}
                setEvents={setEvents}
                currentEvent={currentEvent}
              />
            ))
          }
        </div>
      </DragDropContext>
    </div>
  );
};

export default TaskBox;
