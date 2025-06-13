import { toBRL } from "./utils/toBRL.js";
import { toFormattedDate } from "./utils/toFormattedDate.js";
import { toQueryParams } from "./utils/toQueryParams.js";

import { rootApiUrl, endpointsMap } from "./variables.js";

const { pathname } = location;
let endpoint = endpointsMap.get(pathname);

const orderStatusLabels = {
    pending: "Em aberto",
    payed: "Pago",
    canceled: "Cancelado",
};

const queryParams = {
    offset: 0,
    limit: 20,
    sortByColumn: "id",
    sortByType: "ASC",
    filterByValue: "",
    filterByDate: "",
    filterByStatus: "",
};

window.onload = () => {
    getItems();
    handleDisableCurrentNavItem();
};

Array.from(document.getElementsByClassName("btn-teste")).forEach((button) => {
    button.addEventListener("click", () => {
        console.log("Button clicked:", button.value);
        fetch("http://localhost:8080/api/clients?offset=" + button.value)
            .then(async (response) => {
                const data = await response.json();
                console.log(data);
                teste.innerHTML = data
                    .map((client) => {
                        return `<div class="client">
                                    <h3>${client.name}</h3>
                                    <p>${client.email}</p>
                                </div>`;
                    })
                    .join("");
            })
            .catch((error) => {
                console.error("There was an error!", error);
            });
    });
});

function getItems() {
    const { limit, offset } = queryParams;

    const queryParamsUrl = toQueryParams(queryParams);

    fetch(rootApiUrl + endpoint + queryParamsUrl).then(async (response) => {
        const data = await response.json();

        const { total, items } = data;

        createPagination(total, Math.floor(offset / limit));

        createTableRows(items);
    });
}

function createTableRows(items) {
    const theadElement = document
        .getElementById("table")
        .getElementsByTagName("thead")[0];
    const tbodyElement = document
        .getElementById("table")
        .getElementsByTagName("tbody")[0];

    theadElement.innerHTML = "";
    tbodyElement.innerHTML = "";

    if (endpoint === "clients") {
        theadElement.innerHTML = `
                    <tr>
                        <th>Nome</th>
                        <th>Sobrenome</th>
                        <th>Email</th>
                        <th>CPF</th>
                    </tr>
                `;
        items.forEach((item) => {
            const row = tbodyElement.insertRow();
            row.innerHTML = `
                        <td>${item.name}</td>
                        <td>${item.surname}</td>
                        <td>${item.email ?? "Não cadastrado"}</td>
                        <td>${item.cpf.replace(
                            /(\d{3})(\d{3})(\d{3})(\d{2})/,
                            "$1.$2.$3-$4"
                        )}</td>
                    `;
        });
    } else if (endpoint === "products") {
        theadElement.innerHTML = `
                    <tr>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Código de barras</th>
                    </tr>
                `;
        items.forEach((item) => {
            const row = tbodyElement.insertRow();
            row.innerHTML = `
                        <td>${item.name}</td>
                        <td>${Intl.NumberFormat("pt-BR", {
                            style: "currency",
                            currency: "BRL",
                        }).format(+item.price)}</td>
                        <td>${item.barcode}</td>
                    `;
        });
    } else if (endpoint === "orders") {
        theadElement.innerHTML = `
                    <tr>
                        <th>Nº</th>
                        <th>Cliente</th>
                        <th>Produto</th>
                        <th>Valor unit.</th>
                        <th>Quantidade</th>
                        <th>Desconto</th>
                        <th>Valor total</th>
                        <th>Data do pedido</th>
                        <th>Status</th>
                    </tr>
                `;
        items.forEach((item) => {
            const productPrice = +item.product_price;
            const totalWithDiscount = productPrice * item.quantity;
            const total = totalWithDiscount - totalWithDiscount * item.discount;

            const discount = item.discount * 100;
            const discountPercentage =
                discount > 0 ? discount.toFixed(0) + "%" : "Nenhum";

            const formattedDate = toFormattedDate(item.order_date);

            const row = tbodyElement.insertRow();
            row.innerHTML = `
                        <td>#${item.id}</td>
                        <td>${item.client_name + " " + item.client_surname}</td>
                        <td>${item.product_name}</td>
                        <td>${toBRL(productPrice)}</td>
                        <td>${item.quantity}</td>
                        <td>${discountPercentage}</td>
                        <td>${toBRL(total)}</td>
                        <td>${formattedDate}</td>
                        <td>${orderStatusLabels[item.status]}</td>
                    `;
        });
    }
}

function createPagination(total, currentPage = 0) {
    const totalPages = Math.ceil(total / queryParams.limit);
    const paginationElement = document.getElementById("pagination");
    paginationElement.innerHTML = "";

    createPageItems(totalPages, currentPage);
    createPrevAndNextButtons(totalPages, currentPage);
}

function createPageItems(totalPages, currentPage) {
    const paginationElement = document.getElementById("pagination");

    for (let i = 0; i < totalPages; i++) {
        const onClick = () => {
            queryParams.offset = i * queryParams.limit;

            getItems(endpoint, queryParams);
        };

        const pageItem = document.createElement("li");
        pageItem.className = "page-item";
        if (i === currentPage) {
            pageItem.classList.add("active");
        }
        pageItem.innerHTML = `<a class="page-link" data-page="${i}">${
            i + 1
        }</a>`;
        pageItem
            .getElementsByTagName("a")[0]
            .addEventListener("click", onClick);

        paginationElement.appendChild(pageItem);
    }
}

function createPrevAndNextButtons(totalPages, currentPage) {
    const paginationElement = document.getElementById("pagination");

    const onClickPrev = () => {
        if (currentPage > 0) {
            queryParams.offset = (currentPage - 1) * queryParams.limit;

            getItems(endpoint, queryParams);
        }
    };
    const onClickNext = () => {
        if (currentPage < totalPages - 1) {
            queryParams.offset = (currentPage + 1) * queryParams.limit;

            getItems(endpoint, queryParams);
        }
    };

    const prevItem = document.createElement("li");
    prevItem.className = "page-item" + (currentPage === 0 ? " disabled" : "");
    prevItem.innerHTML =
        '<a class="page-link aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
    prevItem
        .getElementsByTagName("a")[0]
        .addEventListener("click", onClickPrev);

    paginationElement.insertBefore(prevItem, paginationElement.firstChild);

    const nextItem = document.createElement("li");
    nextItem.className =
        "page-item" + (currentPage === totalPages - 1 ? " disabled" : "");
    nextItem.innerHTML =
        '<a class="page-link" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>';
    nextItem
        .getElementsByTagName("a")[0]
        .addEventListener("click", onClickNext);

    paginationElement.appendChild(nextItem);
}

function handleDisableCurrentNavItem() {
    const clientsBtnElement = document.getElementById("clients-btn");
    const productsBtnElement = document.getElementById("products-btn");
    const ordersBtnElement = document.getElementById("orders-btn");

    switch (endpoint) {
        case "clients":
            clientsBtnElement.disabled = true;
            break;
        case "products":
            productsBtnElement.disabled = true;
            break;
        case "orders":
            ordersBtnElement.disabled = true;
    }
}
