import Task from './Task';
import { Droppable, Draggable } from 'react-beautiful-dnd';

const Column = ({ tag, currentEvent, events, setEvents }) => {
  return (
    <div className='column'>
      {tag}
      <Droppable droppableId={tag}>
        {(provided, snapshot) => {
          return (
            <div
              className='task-container'
              ref={provided.innerRef}
              {...provided.droppableProps}
            >
              {events
                .find((event) => event.title === currentEvent.title)
                ?.[tag].map((item, index) => (
                  <Draggable
                    key={item.id}
                    draggableId={item.id.toString()}
                    index={index}
                  >
                    {(provided, snapshot) => (
                      <Task
                        name={item.name}
                        details={item.description ?? "No Description Provided"}
                        id={item.id}
                        provided={provided}
                        snapshot={snapshot}
                      />
                    )}
                  </Draggable>
                ))}
              {provided.placeholder}
            </div>
          );
        }}
      </Droppable>
    </div>
  );
};

export default Column;
