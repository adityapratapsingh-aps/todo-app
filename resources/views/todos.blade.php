<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Todo App</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f4f4;
        padding: 40px;
    }

    .container {
    max-width: 600px;
    margin: 50px auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

    h1 {
        text-align: center;
    }

    form {
        display: flex;
        gap: 10px;
    }

    #todoInput {
        flex: 1;
        padding: 10px;
        font-size: 16px;
    }

    button {
    padding: 8px 14px;
    cursor: pointer;
    border: none;
    border-radius: 5px;
    margin-left: 5px;
}

#todoForm button {
    background: #333;
    color: white;
}

#todoForm button:hover {
    background: #555;
}

li button {
    background: #eee;
}

li button:hover {
    background: #ddd;
}
    }

   li {
    margin-top: 15px;
    padding: 12px;
    background: #f1f1f1;
    list-style: none;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
}
    #todoList {
        padding: 0;
    }
</style>
</head>

<body>

    <h1>My Todo App</h1>

    <form id="todoForm">
        <input
            type="text"
            id="todoInput"
            placeholder="Enter your todo"
            required
        >

        <button type="submit">Add Todo</button>
    </form>

    <ul id="todoList"></ul>
    <script>
    const todoForm = document.getElementById('todoForm');
    const todoInput = document.getElementById('todoInput');
    const todoList = document.getElementById('todoList');

    // Load todos
    async function loadTodos() {
        const response = await fetch('/todos');
        const todos = await response.json();

        todoList.innerHTML = '';

        todos.forEach(todo => {
    const li = document.createElement('li');

    // Checkbox
    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.checked = todo.completed;

    checkbox.addEventListener('change', async function () {
        await fetch(`/todos/${todo.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                completed: checkbox.checked
            })
        });

        loadTodos();
    });

    // Todo text
    const text = document.createElement('span');
    text.textContent = ' ' + todo.title;

 if (todo.completed) {
    text.style.opacity = '0.5';
}
    // Edit button
const editButton = document.createElement('button');
editButton.textContent = ' Edit';

editButton.addEventListener('click', async function () {
    const newTitle = prompt('Edit your todo:', todo.title);

    if (newTitle === null || newTitle.trim() === '') {
        return;
    }

    await fetch(`/todos/${todo.id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            title: newTitle.trim()
        })
    });

    loadTodos();
});

    // Delete button
    const deleteButton = document.createElement('button');
    deleteButton.textContent = ' Delete';

    deleteButton.addEventListener('click', async function () {
        await fetch(`/todos/${todo.id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
            }
        });

        loadTodos();
    });

    li.appendChild(checkbox);
li.appendChild(text);
li.appendChild(editButton);
li.appendChild(deleteButton);
    todoList.appendChild(li);
});
    }

    // Add todo
    todoForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        const title = todoInput.value;

        const response = await fetch('/todos', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                title: title
            })
        });

        if (response.ok) {
            todoInput.value = '';
            loadTodos();
        }
    });

    // Load todos when page opens
    loadTodos();
</script>
</body>
</html>