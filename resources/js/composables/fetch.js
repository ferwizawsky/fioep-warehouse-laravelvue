import { useOption } from "@/stores/option";
import { useNotif } from "@/stores/notif";
import axios from "axios";

export const useFetch = async (method = "GET", request = "/", opts) => {
    const option = useOption();
    let url = option.url;
    try {
        const response = await axios({
            method: method,
            url: `${url}${request}`,
            data: opts,
            headers: {
                Accept: "application/json",
            },
        });
        console.log(response.data);
        return response;
    } catch (error) {
        if (error.response) {
            console.error("Response error:", error.response.data);
            console.error("Status code:", error.response.status);
            console.error("Headers:", error.response.headers);
        } else if (error.request) {
            console.error("No response received:", error.request);
        } else {
            console.error("Request setup error:", error.message);
        }
        throw error;
    }
};

export const useMyFetch = async (method = "GET", request = "/", opts) => {
    const option = useOption();
    const notif = useNotif();
    let header_option = {
        Accept: "application/json",
    };
    let url = option.url;
    if (option.token) {
        header_option = {
            // Authorization: auth?.token,
            authorization: "Bearer " + option?.token,
            Accept: "application/json",
        };
    }
    notif.loading = true;
    try {
        const response = await axios({
            method: method,
            url: `${url}${request}`,
            data: opts,
            headers: header_option,
        });
        // Handle successful response
        console.log(response.data);
        return response;
    } catch (error) {
        // Handle error
        if (error.response) {
            if (error.response.data?.errors) {
                for (let x in error.response.data?.errors) {
                    notif.make(
                        error.response.data?.errors[x]?.length
                            ? error.response.data?.errors[x]?.[0]
                                  ?.replace("field is required", "Wajib Diisi")
                                  ?.replace("The", "Data Input")
                            : error.response.data?.errors[x],
                        {
                            delay: 5000,
                            type: "danger",
                            reverse: false,
                        }
                    );
                }
            } else if (error.response.data?.message) {
                notif.make(error.response.data?.message, {
                    delay: 5000,
                    type: "danger",
                    reverse: false,
                });
            }

            console.error("Response error:", error.response.data);
            console.error("Status code:", error.response.status);
            console.error("Headers:", error.response.headers);
        } else if (error.request) {
            // The request was made but no response was received
            console.error("No response received:", error.request);
        } else {
            // Something happened in setting up the request that triggered an Error
            console.error("Request setup error:", error.message);
        }

        throw error; // rethrow the error to propagate it further if needed
    } finally {
        notif.loading = false;
    }
};

export const useBlobFetch = async (method = "GET", request = "/", opts) => {
    const option = useOption();
    const notif = useNotif();
    let header_option = {
        Accept: "application/json",
    };
    let url = option.url;
    if (option.token) {
        header_option = {
            // Authorization: auth?.token,
            authorization: "Bearer " + option?.token,
            Accept: "application/json",
        };
    }
    notif.loading = true;
    try {
        const response = await axios({
            method: method,
            url: `${url}${request}`,
            data: opts,
            responseType: "blob",
            headers: header_option,
        });
        return response;
    } catch (error) {
        // Handle error
        if (error.response) {
            if (error.response.data?.errors) {
                for (let x in error.response.data?.errors) {
                    notif.make(
                        error.response.data?.errors[x]?.length
                            ? error.response.data?.errors[x]
                            : error.response.data?.errors[x],
                        {
                            delay: 5000,
                            type: "danger",
                            reverse: false,
                        }
                    );
                }
            } else if (error.response.data?.message) {
                notif.make(error.response.data?.message, {
                    delay: 5000,
                    type: "danger",
                    reverse: false,
                });
            }

            console.error("Response error:", error.response.data);
            console.error("Status code:", error.response.status);
            console.error("Headers:", error.response.headers);
        } else if (error.request) {
            // The request was made but no response was received
            console.error("No response received:", error.request);
        } else {
            // Something happened in setting up the request that triggered an Error
            console.error("Request setup error:", error.message);
        }

        throw error; // rethrow the error to propagate it further if needed
    } finally {
        notif.loading = false;
    }
};

export const jsonFormData = (json, exceptional = []) => {
    let fm = new FormData();
    for (let x in json) {
        if (Array.isArray(json[x]) || typeof json[x] === "object") {
            for (let y in json[x]) {
                if (json[x][y]) {
                    if (
                        Array.isArray(json[x][y]) ||
                        (typeof json[x][y] === "object" &&
                            exceptional.indexOf(x) == -1)
                    ) {
                        for (let z in json[x][y]) {
                            if (json[x][y][z]) {
                                fm.append(`${x}[${y}][${z}]`, json[x][y][z]);
                            }
                        }
                    } else {
                        console.log(json[x][y]);
                        fm.append(`${x}[${y}]`, json[x][y]);
                    }
                }
            }
        } else {
            if (json[x]) {
                fm.append(x, json[x]);
            }
        }
    }
    return fm;
};
