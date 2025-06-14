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

let selectedItems = [];

window.onload = () => {
    getItems();

    handleItemsPerPageChange();

    document
        .getElementById("remove-selected-btn")
        .addEventListener("click", (e) => {
            const response = confirm(
                "Deseja confirmar a exclusão dos itens selecionados?"
            );

            if (response) {
                console.log(
                    JSON.stringify({
                        ids: selectedItems.map((id) => +id),
                    })
                );
                fetch(rootApiUrl + "bulk/" + endpoint, {
                    method: "DELETE",
                    body: JSON.stringify({
                        ids: selectedItems.map((id) => +id),
                    }),
                    headers: {
                        "Content-Type": "application/json",
                    },
                }).then(() => {
                    alert("Itens excluídos com sucesso!");

                    selectedItems = [];
                    document.getElementById(
                        "remove-selected-btn"
                    ).disabled = true;

                    getItems();
                });
            }
        });
};

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
                        <th></th>
                        <th>Nome</th>
                        <th>Sobrenome</th>
                        <th>Email</th>
                        <th>CPF</th>
                    </tr>
                `;
        items.forEach((item) => {
            const row = tbodyElement.insertRow();
            row.innerHTML = `
                        <td><input class="form-check-input" type="checkbox" value=${
                            item.id
                        }></td>
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
                        <th></th>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Código de barras</th>
                    </tr>
                `;
        items.forEach((item) => {
            const row = tbodyElement.insertRow();
            row.innerHTML = `
                        <td><input class="form-check-input" type="checkbox" value=${
                            item.id
                        }></td>
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
                        <th></th>
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
                        <td><input class="form-check-input" type="checkbox" value=${
                            item.id
                        }></td>
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

    Array.from(tbodyElement.getElementsByTagName("input")).forEach((input) => {
        input.addEventListener("click", () => {
            const { value, checked } = input;

            if (checked) selectedItems.push(value);
            else
                selectedItems = [
                    ...selectedItems.filter((item) => item != value),
                ];

            document.getElementById("remove-selected-btn").disabled =
                !selectedItems.length;
        });

        const { value } = input;

        if (selectedItems.includes(value) && !input.checked) input.click();
    });
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

function handleItemsPerPageChange() {
    document.getElementById("limit-switch").addEventListener("change", (e) => {
        resetQueryParams();
        queryParams.limit = +e.target.value;

        getItems();
    });
}

function resetQueryParams() {
    queryParams.offset = 0;
    queryParams.limit = 20;
    queryParams.sortByColumn = "id";
    queryParams.sortByType = "ASC";
    queryParams.filterByValue = "";
    queryParams.filterByDate = "";
    queryParams.filterByStatus = "";
}
